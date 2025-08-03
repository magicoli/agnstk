<?php

namespace AGNSTK\Core\Http\Controllers;

use AGNSTK\Core\Services\ConfigService;
use AGNSTK\Core\Services\BlockService;
use AGNSTK\Core\Services\PageService;
use AGNSTK\Core\Services\ShortcodeService;
use AGNSTK\Core\Services\UrlService;
use AGNSTK\Core\Services\SecurityService;
use AGNSTK\Core\Services\ManifestService;
use Exception;

class Controller {
    public function __construct() {
        // Initialize services
        UrlService::initialize();
        BlockService::initialize();
        ShortcodeService::initialize();
        
        // Ensure API keys are generated
        SecurityService::ensureApiKeys();
    }
    
    /**
     * Default home page - redirect to configured home page
     */
    public function index() {
        $config = ConfigService::getConfig();
        $homePage = $config['site']['home_page'] ?? 'hello';
        
        // Render the home page with special flag
        return $this->page($homePage, true);
    }
    
    /**
     * Render a specific page using Laravel UI Bootstrap layout
     */
    public function page(string $pageId, bool $isHomePage = false) {
        $pageConfig = PageService::getPage($pageId);
        
        if (!$pageConfig || !($pageConfig['enabled'] ?? true)) {
            return response('Page not found', 404);
        }

        $title = $pageConfig['title'] ?? ucfirst($pageId);
        
        // Handle special content types
        if (isset($pageConfig['content']) && $pageConfig['content'] === 'shortcode-demo') {
            $content = $this->renderShortcodeDemo();
        } else {
            $content = PageService::renderPage($pageId);
        }

        // Use simple Bootstrap layout
        try {
            $templatePath = '/home/magic/domains/agnstk.org/www/agnstk/core/resources/views/content.blade.php';
            $template = file_get_contents($templatePath);
            $html = str_replace('{!! $content !!}', $content, $template);
            $html = str_replace("{{ config('app.name', 'AGNSTK') }}", 'AGNSTK', $html);
            return $html;
        } catch (Exception $e) {
            return "<!DOCTYPE html><html><head><title>{$title}</title></head><body>{$content}</body></html>";
        }
    }

    /**
     * Render a specific block using Bootstrap layout
     */
    public function block(string $blockId) {
        $blockContent = BlockService::renderBlock($blockId);
        
        if (empty($blockContent)) {
            return response('Block not found', 404);
        }
        
        try {
            $templatePath = '/home/magic/domains/agnstk.org/www/agnstk/core/resources/views/content.blade.php';
            $template = file_get_contents($templatePath);
            $html = str_replace('{!! $content !!}', $blockContent, $template);
            $html = str_replace("{{ config('app.name', 'AGNSTK') }}", 'AGNSTK', $html);
            return $html;
        } catch (Exception $e) {
            return response("<!DOCTYPE html><html><head><title>Block</title></head><body>{$blockContent}</body></html>");
        }
    }
    
    /**
     * Render shortcode demo content
     */
    protected function renderShortcodeDemo(): string {
        $shortcodes = ShortcodeService::getRegisteredShortcodes();
        $blocks = BlockService::getEnabledBlocks();
        
        $html = '<div class="agnstk-page">';
        $html .= '<h1>Shortcode Demo</h1>';
        $html .= '<p class="lead">This page demonstrates the available shortcodes and how they work.</p>';
        
        if (!empty($shortcodes)) {
            $html .= '<h2>Available Shortcodes</h2>';
            
            foreach ($shortcodes as $shortcode) {
                // Find the block that uses this shortcode
                $block = null;
                foreach ($blocks as $blockId => $blockInstance) {
                    if ($blockInstance->getShortcode() === $shortcode) {
                        $block = $blockInstance;
                        break;
                    }
                }
                
                if ($block) {
                    $html .= '<div class="card mb-3">';
                    $html .= '<div class="card-header"><h5>[' . esc_html($shortcode) . ']</h5></div>';
                    $html .= '<div class="card-body">';
                    $html .= '<p class="card-text">' . esc_html($block->getDescription()) . '</p>';
                    
                    // Show basic example
                    $html .= '<div class="alert alert-info">';
                    $html .= '<strong>Example:</strong> <code>[' . esc_html($shortcode) . ']</code>';
                    $html .= '</div>';
                    
                    // Show live example
                    $html .= '<div class="alert alert-success">';
                    $html .= '<strong>Result:</strong><br>';
                    $html .= ShortcodeService::processContent('[' . $shortcode . ']');
                    $html .= '</div>';
                    
                    $html .= '</div></div>';
                }
            }
        } else {
            $html .= '<div class="alert alert-warning">No shortcodes are currently available.</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * API endpoint for block metadata
     */
    public function api() {
        // Check API authentication - only accept via header or POST for security
        $apiKey = $_POST['key'] ?? $_SERVER['HTTP_X_API_KEY'] ?? '';
        
        if (!SecurityService::validateApiKey($apiKey, 'read_only')) {
            return response()->json([
                'error' => 'Invalid or missing API key',
                'message' => 'Please provide a valid API key via X-API-Key header or POST parameter'
            ], 401);
        }
        
        return response()->json([
            'app' => ConfigService::getApp(),
            'blocks' => BlockService::getBlocksMetadata(),
            'pages' => PageService::getPageMetadata(),
            'shortcodes' => ShortcodeService::getRegisteredShortcodes()
        ]);
    }
    
    /**
     * Display error page
     */
    public function errorPage($title = 'Error', $message = 'An error occurred.') {
        try {
            $templatePath = '/home/magic/domains/agnstk.org/www/agnstk/core/resources/views/content.blade.php';
            $template = file_get_contents($templatePath);
            $html = str_replace('{!! $content !!}', "<div class=\"alert alert-danger\">{$message}</div>", $template);
            $html = str_replace("{{ config('app.name', 'AGNSTK') }}", $title, $html);
            return $html;
        } catch (Exception $e) {
            return "<!DOCTYPE html><html><head><title>{$title}</title></head><body><div class=\"alert alert-danger\">{$message}</div></body></html>";
        }
    }
    
    /**
     * Serve web app manifest.json
     */
    public function manifest() {
        ManifestService::serveManifest();
        return '';
    }
}
