<?php
/**
 * Plugin Name: ProfilePress Address Autocomplete (Web Component)
 * Description: Adds address autocomplete to ProfilePress using Google's gmp-place-autocomplete Web Component.
 * Version: 1.1
 * Author: Kite Web Design
 * Author URI: https://kitewebdesign.co.uk
 * License: GPL2
 */

add_action('wp_enqueue_scripts', 'pp_enqueue_autocomplete_loader');

function pp_enqueue_autocomplete_loader() {
    // Load official loader
    wp_enqueue_script(
        'google-maps-loader',
        'https://unpkg.com/@googlemaps/js-api-loader@1.15.1/dist/index.min.js',
        [],
        null,
        true
    );

    // Inline script that uses the loader to initialise Autocomplete properly
    wp_add_inline_script('google-maps-loader', <<<JS
        document.addEventListener("DOMContentLoaded", function () {
    const loader = new google.maps.plugins.loader.Loader({
        //apiKey: "AIzaSyAy95TS0OGZcUSufSpr4DhYAa8AjfUhidM",
        apiKey: "AIzaSyA9aKGCwTHxhmfc6A0BTBLl8wfh9FnbcUY",
        version: "weekly",
        libraries: ["places"]
    });

    loader.load().then(() => {
        const input = document.querySelector(".address-lookup");
        if (!input) return;

        input.setAttribute("placeholder", "Start typing your address...");
        input.style.width = "100%";
        input.style.padding = "0.5em";
        input.style.border = "1px solid #ccc";
        input.style.boxSizing = "border-box";

        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'],
            componentRestrictions: { country: 'gb' }
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (!place || !place.address_components) return;

            const comps = {};
            for (const c of place.address_components) {
                for (const t of c.types) {
                    comps[t] = c.long_name;
                }
            }

            const get = (type) => comps[type] || "";
            const set = (sel, val) => {
                const el = document.querySelector(sel);
                if (el) el.value = val;
            };

            set('.address-street', (get('street_number') + ' ' + get('route')).trim());
            set('.address-city', get('locality') || get('postal_town'));
            set('.address-state', get('administrative_area_level_2') || get('administrative_area_level_1'));
            set('.address-zip', get('postal_code'));
            
            const countrySelect = document.querySelector('.address-country');

            if (countrySelect && place.address_components) {
                const countryComp = place.address_components.find(c => c.types.includes('country'));
                if (countryComp && countryComp.short_name) {
                    const countryCode = countryComp.short_name; // e.g. "GB", "US", etc.
                    const optionExists = Array.from(countrySelect.options).some(opt => opt.value === countryCode);
                    if (optionExists) {
                        countrySelect.value = countryCode;
                    }
                }
            }



            if (place.geometry && place.geometry.location) {
                set('.address-lat', place.geometry.location.lat());
                set('.address-lng', place.geometry.location.lng());
            }
        });

        // ✅ Disable editing of visible address fields
        const fieldsToDisable = ['.address-street', '.address-city', '.address-state', '.address-zip', '.address-country'];
        fieldsToDisable.forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                if (el.tagName.toLowerCase() === 'select') {
                    el.setAttribute('disabled', 'disabled');
                } else {
                    el.setAttribute('readonly', 'readonly');
                }
                el.style.backgroundColor = '#f9f9f9';
                el.style.cursor = 'not-allowed';
            });
        });

        // ✅ Hide lat/lng fields completely
        const hiddenCoords = ['.reg-cpf-address-lattext', '.reg-cpf-address-longtext'];
        hiddenCoords.forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                el.style.display = 'none';
                if (el.closest('label')) el.closest('label').style.display = 'none';
            });
        });
    });

    const form = document.querySelector('.pp-registration-form-wrapper form'); // Adjust this selector if needed
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const cimspa = document.querySelector('.cimspa-number');
        if (!cimspa) return;

        const value = cimspa.value.trim();
        const pattern = /^C\d{6}$/;

        // Clear any previous error
        let existing = document.querySelector('.cimspa-error');
        if (existing) existing.remove();

        if (!pattern.test(value)) {
            e.preventDefault();

            // Add error message
            const error = document.createElement('div');
            error.className = 'cimspa-error';
            error.textContent = 'Please enter a valid CIMSPA number (e.g. C013230)';
            error.style.color = 'red';
            error.style.marginTop = '0.5em';

            cimspa.insertAdjacentElement('afterend', error);
        }
    });

});

JS
    );
}
