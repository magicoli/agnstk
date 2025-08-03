<?php

namespace AGNSTK\Core\Services;

use Illuminate\Support\Facades\Config;
use Exception;

class ConfigService {
    protected static $config = null;
    protected static $appDefaults = null;

    /**
     * Get the full AGNSTK configuration
     */
    public static function getConfig(): array {
        if (self::$config === null) {
            self::loadConfig();
        }
        
        return self::$config;
    }

    /**
     * Get app defaults configuration
     */
    public static function getAppDefaults(): array {
        if (self::$appDefaults === null) {
            self::loadAppDefaults();
        }
        
        return self::$appDefaults;
    }

    /**
     * Get a specific configuration value using dot notation
     */
    public static function get(string $key, $default = null) {
        $config = self::getConfig();
        
        return self::dataGet($config, $key, $default);
    }
    
    /**
     * Simple helper for dot notation array access (standalone fallback for data_get)
     */
    private static function dataGet($target, $key, $default = null) {
        if (function_exists('data_get')) {
            return data_get($target, $key, $default);
        }
        
        if (is_null($key)) {
            return $target;
        }
        
        $key = is_array($key) ? $key : explode('.', $key);
        
        foreach ($key as $segment) {
            if (is_array($target) && array_key_exists($segment, $target)) {
                $target = $target[$segment];
            } else {
                return $default;
            }
        }
        
        return $target;
    }

    /**
     * Load configuration from config.json file
     */
    protected static function loadConfig(): void {
        // Start with app defaults as base
        $appDefaults = self::getAppDefaults();
        
        // Try to use Laravel config if available
        $configFile = null;
        
        try {
            $configFile = Config::get('agnstk.config_file');
        } catch (Exception $e) {
            // Fallback for standalone mode
            $configFile = __DIR__ . '/../../../config.json';
        }

        // Load site-specific config and merge with app defaults
        if (file_exists($configFile)) {
            $fileConfig = json_decode(file_get_contents($configFile), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                self::$config = self::mergeConfigArrays($appDefaults, $fileConfig);
            } else {
                self::$config = $appDefaults;
            }
        } else {
            self::$config = $appDefaults;
        }
    }

    /**
     * Load app defaults from PHP file
     */
    protected static function loadAppDefaults(): void {
        $defaultsFile = __DIR__ . '/../../config/app-defaults.php';
        
        if (file_exists($defaultsFile)) {
            self::$appDefaults = require $defaultsFile;
        } else {
            self::$appDefaults = [
                                'pages' => [],
                'routes' => ['public' => [], 'admin' => []],
                'site' => ['home_page' => null, 'menu_pages' => []]
            ];
        }
    }


    /**
     * Get app information
     */
    public static function getApp(): array {
        return self::get('app', []);
    }

    /**
     * Get all blocks configuration
     */
    public static function getBlocks(): array {
        return self::get('blocks', []);
    }

    /**
     * Get a specific block configuration
     */
    public static function getBlock(string $blockId): ?array {
        return self::get("blocks.{$blockId}");
    }

    /**
     * Get all pages configuration
     */
    public static function getPages(): array {
        return self::get('pages', []);
    }

    /**
     * Get a specific page configuration
     */
    public static function getPage(string $pageId): ?array {
        return self::get("pages.{$pageId}");
    }

    /**
     * Get routes configuration
     */
    public static function getRoutes(): array {
        return self::get('routes', []);
    }

    /**
     * Custom merge that preserves string values and doesn't convert them to arrays
     */
    protected static function mergeConfigArrays(array $defaults, array $overrides): array {
        $result = $defaults;
        
        foreach ($overrides as $key => $value) {
            if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                // Only recurse if both values are arrays
                $result[$key] = self::mergeConfigArrays($result[$key], $value);
            } else {
                // Override with the new value (preserves strings, numbers, etc.)
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * Clear cached configuration to force reload
     */
    public static function clearCache(): void {
        self::$config = null;
    }
}
