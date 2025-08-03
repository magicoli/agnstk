<?php
/**
 * Router script for PHP built-in server
 * Handles static file serving and routing
 */

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Handle static files from core/public directory
if (preg_match('/^\/core\/public\/(.+)$/', $requestUri, $matches)) {
    $filePath = __DIR__ . '/core/public/' . $matches[1];
    
    if (file_exists($filePath) && is_file($filePath)) {
        // Get file extension and set appropriate content type
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $contentTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $contentType = $contentTypes[$ext] ?? 'application/octet-stream';
        header('Content-Type: ' . $contentType);
        
        // Set cache headers for assets
        header('Cache-Control: public, max-age=31536000'); // 1 year
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        readfile($filePath);
        return;
    }
}

// For all other requests, use the main index.php
require __DIR__ . '/index.php';
