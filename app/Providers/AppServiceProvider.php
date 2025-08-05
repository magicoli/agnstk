<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure global URL handling for root-based serving
        $this->configureGlobalUrls();
    }

    /**
     * Configure global URL handling with base URL detection
     */
    private function configureGlobalUrls(): void
    {
        $request = request();
        
        // Auto-detect the base URL from the request
        $scheme = $request->getScheme();
        $host = $request->getHttpHost();
        $scriptName = $request->getScriptName();
        
        // Extract base path (handles subdirectory installations)
        $basePath = dirname($scriptName);
        if ($basePath === '/' || $basePath === '\\') {
            $basePath = '';
        }
        
        $baseUrl = $scheme . '://' . $host . $basePath;
        $publicUrl = $baseUrl . '/public';
        
        // Configure Laravel's URL generation
        URL::forceRootUrl($baseUrl);
        
        // Store URLs in config for global access
        config(['app.detected_base_url' => $baseUrl]);
        config(['app.detected_public_url' => $publicUrl]);
        
        // Create global helper macros
        URL::macro('baseUrl', function ($path = '') {
            $baseUrl = config('app.detected_base_url');
            return $baseUrl . ($path ? '/' . ltrim($path, '/') : '');
        });
        
        URL::macro('publicUrl', function ($path = '') {
            $publicUrl = config('app.detected_public_url');
            return $publicUrl . ($path ? '/' . ltrim($path, '/') : '');
        });
    }
}
