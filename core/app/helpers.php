<?php
/**
 * Global helper functions for AGNSTK
 */

if (!function_exists('esc_html')) {
    /**
     * Escape HTML characters for safe output
     * Alias for Laravel's e() function
     */
    function esc_html($text) {
        return e($text);
    }
}
