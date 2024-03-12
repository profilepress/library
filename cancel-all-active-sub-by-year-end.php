<?php

/** Cancels all active/trialling subscriptions by the end of the year */
add_action('admin_init', function () {

    $isLastDateAndHourOfYear = function () {
        $now = new DateTimeImmutable("now", wp_timezone());

        $year = $now->format("Y");

        // Set the date to December 31st of the current year
        $lastHour = (new DateTimeImmutable("$year-12-31", wp_timezone()))->setTime(23, 59, 59);

        // Check if the current time is the last day and hour of the year
        return $now >= $lastHour;
    };

    if ($isLastDateAndHourOfYear()) {

        $subs = ProfilePress\Core\Membership\Repositories\SubscriptionRepository
            ::init()
            ->retrieveBy([
                'number' => 9999,
                'status' => [
                    'trialling',
                    'active'
                ]
            ]);

        foreach ($subs as $sub) {
            $sub->cancel(true);
        }
    }
});
