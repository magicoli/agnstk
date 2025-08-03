<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AGNSTK Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration is loaded from the root config.json file and provides
    | all the settings for blocks, pages, routes, and general app configuration.
    |
    */

    'config_file' => base_path('../config.json'),
    
    // Default configuration (fallback if config.json is not found)
    'defaults' => [
        'app' => [
            'name' => 'AGNSTK Example App',
            'version' => '1.0.0',
            'description' => 'Agnostic Glue for Non-Specific ToolKits - Example Application'
        ],
        'blocks' => [],
        'pages' => [],
        'routes' => [
            'public' => [],
            'admin' => []
        ]
    ]
];
