<?php

define('APP_ROOT', __DIR__);


return [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,

        'doctrine' => [
            'dev_mode' => true,
            'type' =>'annotation',
            'auto_mapping' => true,
            'cache_dir' => APP_ROOT . '/var/cache/doctrine',
            'metadata_dirs' => [APP_ROOT . '/src/Domain'],
            'connection' => [
                'driver' => 'pdo_mysql',
                'charset' => 'utf8',
                'url' => 'mysql://iwq:pass@db/iwq?serverVersion=5.7'
            ]
        ]
    ]
];
