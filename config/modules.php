<?php

return [
    'path' => app_path('Modules'),

    'folders' => [
        'Actions',
        'Controllers',
        'Models',
        'Requests',
        'Resources',
        'Rules',
        'Events',
        'Listeners',
        'Jobs',
        'Routes',
        'Services',
        'Views',
    ],

    'routes' => [
        'web' => [
            'enabled' => true,
            'middleware' => ['web'],
            'prefix' => null,
        ],
        'api' => [
            'enabled' => true,
            'middleware' => ['api'],
            'prefix' => 'api',
        ],
    ],
];
