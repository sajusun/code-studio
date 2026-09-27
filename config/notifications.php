<?php

return [

    'channels' => [

        'database'  => env('NOTIFICATION_DATABASE', true),

        'firebase'  => env('NOTIFICATION_FIREBASE', false),

        'broadcast' => env('NOTIFICATION_BROADCAST', true),

        'mail'      => env('NOTIFICATION_MAIL', false),

    ],

];
