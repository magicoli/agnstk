<?php

namespace AGNSTK\Core\Services;

class UrlService {
    private static $basePath = null;
    
    /**
     * Initialize the base path from the request
     */
    public static function initialize() {
        if (self::$basePath === null) {
            // Detect base path from script location
            $scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            self::$basePath = rtrim($scriptPath, '/');
        }
    }
    
    /**
     * Generate a URL with the correct base path
     */
    public static function to(string $path): string {
        self::initialize();
        
        // Ensure path starts with /
        $path = '/' . ltrim($path, '/');
        
        // Combine base path with the route
        return self::$basePath . $path;
    }
    
    /**
     * Get the current base path
     */
    public static function getBasePath(): string {
        self::initialize();
        return self::$basePath;
    }
    
    /**
     * Generate URL for a page by ID
     */
    public static function page(string $pageId): string {
        $config = ConfigService::getConfig();
        $routes = $config['routes']['public'] ?? [];
        
        if (isset($routes[$pageId])) {
            return self::to($routes[$pageId]);
        }
        
        // Fallback to page ID
        return self::to('/' . $pageId);
    }
    
    /**
     * Generate URL for a block
     */
    public static function block(string $blockId): string {
        return self::to('/block/' . $blockId);
    }
    
    /**
     * Generate URL for API
     */
    public static function api(): string {
        return self::to('/api');
    }
}
