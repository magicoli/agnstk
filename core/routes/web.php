<?php

use Illuminate\Support\Facades\Route;
use AGNSTK\Core\Http\Controllers\Controller;

// Home page
Route::get('/', [Controller::class, 'index']);

// Dynamic page routes based on configuration
Route::get('/hello', [Controller::class, 'page'])->defaults('pageId', 'hello');

// Block preview routes
Route::get('/block/{blockId}', [Controller::class, 'block']);

// Shortcodes
Route::get('/shortcode-demo', [Controller::class, 'shortcodeDemo']);

// API endpoint
Route::get('/api', [Controller::class, 'api']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
