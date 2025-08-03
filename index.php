<?php
/**
 * Standalone entry point for AGNSTK Example App
 * Simple demo using our Controller directly
 */

// Load autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel's autoloader from core
require_once __DIR__ . '/core/vendor/autoload.php';

// Bootstrap Laravel for proper templating support
$app = require_once __DIR__ . '/core/bootstrap/app.php';

use AGNSTK\Core\Http\Controllers\Controller;

try {
    // Get the request path and remove the base path (subfolder)
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    
    // Remove the subfolder path to get the clean route
    $path = '/' . ltrim(substr($requestUri, strlen($scriptPath)), '/');
    $path = rtrim($path, '/') ?: '/';
    
    // Simple routing
    $controller = new Controller();
    
    // Get routes from configuration
    $config = \AGNSTK\Core\Services\ConfigService::getConfig();
    $routes = $config['routes']['public'] ?? [];
    
    // Handle root path
    if ($path === '/') {
        $response = $controller->index();
    }
    // Check configured routes for pages
    elseif (isset($routes) && ($pageId = array_search($path, $routes)) !== false) {
        $response = $controller->page($pageId);
    }
    // Check for block routes (convention: /block/{blockId})
    elseif (preg_match('/^\/block\/(.+)$/', $path, $matches)) {
        $response = $controller->block($matches[1]);
    }
    // Check for built-in routes
    elseif ($path === '/api') {
        $response = $controller->api();
    }
    elseif ($path === '/manifest.json') {
        $response = $controller->manifest();
    }
    elseif(file_exists(__DIR__ . $requestUri)) {
        // Serve static files directly. 
        // Workaround for development test serverrs (e.g. php -S localhost:8080 index.php )
        // Web server should handle this properly in production.

        // Allowed content types
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
        
        $filePath = __DIR__ . $requestUri;

        // Get file extension and set appropriate content type
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);

        $contentType = $contentTypes[$ext] ?? false;
        if($contentType) {
            header('Content-Type: ' . $contentType);
            
            // Set cache headers for assets
            header('Cache-Control: public, max-age=31536000'); // 1 year
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            
            readfile($filePath);
            exit; // Stop further processing
        } else {
            // If no content type found, return forbidden
            http_response_code(403);
            $response = $controller->errorPage(403, 'Forbidden', 'Unsupported file type: ' . $ext);
        }
    }
    // Handle 404 for unknown routes
    else {
        error_log("404 Not Found: " . $path . ' requesturi=' . $requestUri);
        http_response_code(404);
        $response = $controller->errorPage(404, 'Page not found');
    }
    
    // Send response
    if (is_object($response) && method_exists($response, 'send')) {
        $response->send();
    } else {
        echo $response;
    }
    
} catch (Exception $e) {
    // Log the error
    error_log("AGNSTK Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Use proper error page templating
    try {
        $controller = new Controller();
        echo $controller->errorPage(500, 'Internal Server Error', $e->getMessage(), $e->getTraceAsString());
    } catch (Exception $fallbackException) {
        // Log the fallback error too
        error_log("AGNSTK Fallback Error: " . $fallbackException->getMessage());
        
        // Ultimate fallback
        echo "";
        die("<h1>Critical Error</h1><p>A critical error occurred and the error handler failed as well.</p>");
    }
}
