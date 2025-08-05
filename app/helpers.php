<?php

/**
 * AGNSTK Global Helper Functions
 */

if (!function_exists('base_url')) {
    /**
     * Generate a URL with the detected base URL
     */
    function base_url($path = '')
    {
        return \Illuminate\Support\Facades\URL::baseUrl($path);
    }
}

if (!function_exists('public_url')) {
    /**
     * Generate a URL for public assets with the detected public URL
     */
    function public_url($path = '')
    {
        return \Illuminate\Support\Facades\URL::publicUrl($path);
    }
}

if (!function_exists('build_asset')) {
    /**
     * Generate a URL for built assets (CSS/JS) using Vite manifest
     */
    function build_asset($filename)
    {
        $manifestPath = public_path('build/manifest.json');
        
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            
            // Map logical names to actual manifest entries
            $assetMap = [
                'agnstk.css' => 'resources/sass/app.scss',
                'agnstk.js' => 'resources/js/app.js',
            ];
            
            $manifestKey = $assetMap[$filename] ?? $filename;
            
            if (isset($manifest[$manifestKey])) {
                return public_url('build/' . $manifest[$manifestKey]['file']);
            }
        }
        
        // Fallback: if the exact filename doesn't exist, try to find similar one
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            foreach ($manifest as $key => $entry) {
                // Match agnstk.js to any agnstk*.js file
                if ($filename === 'agnstk.js' && strpos($entry['file'], 'agnstk') !== false && strpos($entry['file'], '.js') !== false) {
                    return public_url('build/' . $entry['file']);
                }
                // Match agnstk.css to any agnstk*.css file
                if ($filename === 'agnstk.css' && strpos($entry['file'], 'agnstk') !== false && strpos($entry['file'], '.css') !== false) {
                    return public_url('build/' . $entry['file']);
                }
            }
        }
        
        // Final fallback to direct filename
        return public_url("build/assets/{$filename}");
    }
}
