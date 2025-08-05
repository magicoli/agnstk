<?php

/**
 * AGNSTK - Agnostic Glue for Non-Specific ToolKits
 * 
 * This is the standalone entry point for the AGNSTK Laravel application.
 * It allows the app to be accessed directly from the project root.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Detect base URL from the current request for proper asset handling
$request = Request::capture();

// Auto-detect the base URL and path
$scheme = $request->getScheme();
$host = $request->getHttpHost();
$scriptName = $request->getScriptName();

// Extract the base path (handles subdirectory installations like /agnstk/)
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

// Build the complete base URL
$baseUrl = $scheme . '://' . $host . $basePath;

// Set environment variables for Laravel's URL generation
putenv("APP_URL={$baseUrl}");
$_ENV['APP_URL'] = $baseUrl;

// Set asset URL to point to the public directory within our installation
putenv("ASSET_URL={$baseUrl}/public");
$_ENV['ASSET_URL'] = $baseUrl . '/public';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

// Configure Laravel after bootstrapping
$app->booted(function ($app) use ($baseUrl) {
    // Set the application URL
    $app['config']->set('app.url', $baseUrl);
    // Set the asset URL for proper asset() helper behavior
    $app['config']->set('app.asset_url', $baseUrl . '/public');
});

$app->handleRequest($request);
