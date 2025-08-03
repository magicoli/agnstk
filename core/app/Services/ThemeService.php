<?php

namespace AGNSTK\Core\Services;

class ThemeService {
    private static array $registeredStyles = [];
    private static array $registeredScripts = [];
    
    /**
     * Register a CSS file
     */
    public static function addStyle(string $handle, string $path, array $deps = [], string $version = '1.0.0'): void {
        self::$registeredStyles[$handle] = [
            'path' => $path,
            'deps' => $deps,
            'version' => $version
        ];
    }
    
    /**
     * Register a JS file
     */
    public static function addScript(string $handle, string $path, array $deps = [], string $version = '1.0.0'): void {
        self::$registeredScripts[$handle] = [
            'path' => $path,
            'deps' => $deps,
            'version' => $version
        ];
    }
    
    /**
     * Get current theme from config
     */
    public static function getCurrentTheme(): string {
        $config = ConfigService::getConfig();
        return $config['site']['theme'] ?? 'default';
    }
    
    /**
     * Get theme path
     */
    public static function getThemePath(string $theme = null): string {
        $theme = $theme ?? self::getCurrentTheme();
        return "/core/resources/themes/{$theme}";
    }
    
    /**
     * Get theme layout path
     */
    public static function getThemeLayout(string $layout = 'app', string $theme = null): string {
        $theme = $theme ?? self::getCurrentTheme();
        return "themes.{$theme}.layouts.{$layout}";
    }
    
    /**
     * Check if theme layout exists
     */
    public static function themeLayoutExists(string $layout = 'app', string $theme = null): bool {
        $theme = $theme ?? self::getCurrentTheme();
        $viewPath = "themes.{$theme}.layouts.{$layout}";
        
        try {
            if (function_exists('app') && app()->bound('view')) {
                return app('view')->exists($viewPath);
            }
        } catch (\Exception $e) {
            // Fall through
        }
        
        return false;
    }
    
    /**
     * Get registered styles with dependency order
     */
    public static function getRegisteredStyles(): array {
        return self::resolveAssetDependencies(self::$registeredStyles);
    }
    
    /**
     * Get registered scripts with dependency order
     */
    public static function getRegisteredScripts(): array {
        return self::resolveAssetDependencies(self::$registeredScripts);
    }
    
    /**
     * Resolve asset dependencies and return ordered array
     */
    private static function resolveAssetDependencies(array $assets): array {
        $resolved = [];
        $visited = [];
        
        foreach (array_keys($assets) as $handle) {
            self::resolveDependency($handle, $assets, $resolved, $visited);
        }
        
        return $resolved;
    }
    
    private static function resolveDependency(string $handle, array $assets, array &$resolved, array &$visited): void {
        if (isset($visited[$handle])) {
            return;
        }
        
        $visited[$handle] = true;
        
        if (!isset($assets[$handle])) {
            return;
        }
        
        $asset = $assets[$handle];
        
        // Resolve dependencies first
        foreach ($asset['deps'] as $dep) {
            self::resolveDependency($dep, $assets, $resolved, $visited);
        }
        
        $resolved[$handle] = $asset;
    }
    
    /**
     * Initialize default theme assets
     */
    public static function initializeDefaultAssets(): void {
        // Register core CSS
        self::addStyle('agnstk-core', '/core/resources/css/agnstk.css', [], '1.0.0');
        
        // Register theme-specific CSS if it exists
        $theme = self::getCurrentTheme();
        $themeCssPath = "/core/resources/themes/{$theme}/style.css";
        
        // For now, assume theme CSS exists if theme is not 'default'
        if ($theme !== 'default') {
            self::addStyle('agnstk-theme', $themeCssPath, ['agnstk-core'], '1.0.0');
        }
    }
    
    /**
     * Render style tags
     */
    public static function renderStyles(): string {
        $styles = self::getRegisteredStyles();
        $output = '';
        
        foreach ($styles as $handle => $asset) {
            $url = UrlService::to($asset['path']);
            $version = $asset['version'];
            $output .= "<link rel=\"stylesheet\" href=\"{$url}?v={$version}\" id=\"{$handle}-css\">\n";
        }
        
        return $output;
    }
    
    /**
     * Render script tags
     */
    public static function renderScripts(): string {
        $scripts = self::getRegisteredScripts();
        $output = '';
        
        foreach ($scripts as $handle => $asset) {
            $url = UrlService::to($asset['path']);
            $version = $asset['version'];
            $output .= "<script src=\"{$url}?v={$version}\" id=\"{$handle}-js\"></script>\n";
        }
        
        return $output;
    }
}
