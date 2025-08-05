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
     * Generate a URL for built assets (CSS/JS)
     */
    function build_asset($filename)
    {
        return public_url("build/assets/{$filename}");
    }
}
