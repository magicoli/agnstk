<?php

return [
    'app' => [
        'name' => 'AGNSTK - Agnostic Glue for Non-Specific ToolKits',
        'version' => '1.0.0',
        'description' => 'Agnostic Glue for Non-Specific ToolKits'
    ],
    'blocks' => [
        'hello' => [
            'title' => 'Hello, Agnostic World',
            'description' => 'A simple greeting block demonstrating AGNSTK capabilities',
            'shortcode' => 'agnstk-hello',
            'enabled' => true
        ]
    ],
    'pages' => [
        'hello' => [
            'title' => 'Hello',
            'slug' => 'hello',
            'blocks' => ['hello'],
            'enabled' => true
        ],
        'shortcode-demo' => [
            'title' => 'Shortcode Demo',
            'slug' => 'shortcode-demo',
            'content' => 'shortcode-demo', // Special content type
            'enabled' => true
        ]
    ],
    'routes' => [
        'public' => [
            'hello' => '/hello',
            'shortcode-demo' => '/shortcode-demo'
        ],
        'admin' => [
            'settings' => '/admin/settings'
        ],
        'api' => [
            'manifest' => '/manifest.json'
        ]
    ],
    'site' => [
        'name' => 'AGNSTK Demo Site',
        'slogan' => 'One app. Many CMS.',
        'theme' => 'default',
        'theme_color' => '#2563eb',
        'home_page' => 'hello',
        'menu_pages' => ['hello', 'shortcode-demo'],
        'logo' => '/core/public/assets/images/logo.png',
        'favicon' => '/core/public/assets/images/favicon.ico',
        'icons' => [
            'apple_touch_icon' => '/core/public/assets/images/apple-touch-icon.png',
            'android_chrome_192' => '/core/public/assets/images/android-chrome-192x192.png',
            'android_chrome_512' => '/core/public/assets/images/android-chrome-512x512.png',
            'safari_pinned_tab' => '/core/public/assets/images/safari-pinned-tab.svg',
            'web_manifest' => '/manifest.json'
        ]
    ],
    'api' => [
        'enabled' => true,
        'require_auth' => true,
        'endpoints' => [
            'blocks' => '/api/blocks',
            'config' => '/api/config',
            'pages' => '/api/pages'
        ],
        'rate_limit' => [
            'requests_per_minute' => 60,
            'burst_limit' => 10
        ]
    ],
    'security' => [
        'csrf_protection' => true,
        'xss_protection' => true,
        'content_security_policy' => true,
        'config_protection' => true
    ]
];
