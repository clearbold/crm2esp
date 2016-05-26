<?php
return [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        'cm' => [
            'clients' => [
                'name' => '',
                'clientId' => '',
                'clientApiKey' => '',
                'subscriberLists' => [
                    '',
                ]
            ]
        ],

        'crm' => [
            'sources' => []
        ],

        'provider' => '\Crm2Esp\GenericCrm',

        'auth' => [
            'user' => 'password'
        ],

        'database' => [
            'server' => '',
            'username' => '',
            'password' => '',
            'database' => ''
        ]
    ],
];
