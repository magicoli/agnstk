<?php
require_once 'vendor/autoload.php';
require_once 'core/vendor/autoload.php';

use AGNSTK\Core\Services\ConfigService;

echo "=== LOGO DEBUG ===\n";

// Test app defaults directly
$appDefaults = require 'core/config/app-defaults.php';
echo "App defaults logo: " . ($appDefaults['site']['logo'] ?? 'NOT SET') . "\n";

// Test site config directly  
$siteConfig = json_decode(file_get_contents('config.json'), true);
echo "Site config logo: " . ($siteConfig['site']['logo'] ?? 'NOT SET') . "\n";

// Test merged config
$config = ConfigService::getConfig();
echo "Merged config logo: " . ($config['site']['logo'] ?? 'NOT SET') . "\n";
echo "Logo type: " . gettype($config['site']['logo'] ?? null) . "\n";

// Test if it's a string
$logo = $config['site']['logo'] ?? '';
echo "Is string: " . (is_string($logo) ? 'YES' : 'NO') . "\n";
echo "Logo length: " . strlen($logo) . "\n";
echo "Logo value: '" . $logo . "'\n";
