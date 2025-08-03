<?php

namespace AGNSTK\Core\Blocks;

use AGNSTK\Core\Services\BaseBlock;
use AGNSTK\Core\Services\ConfigService;

class HelloBlock extends BaseBlock {
    /**
     * Render the Hello, Agnostic World block
     */
    public function render(array $attributes = []): string {
        $app = ConfigService::getApp();
        $appName = $app['name'] ?? 'AGNSTK';
        
        // Get custom message from attributes or use default
        $message = $attributes['message'] ?? 'Hello, Agnostic World!';
        
        // Build the HTML output
        $html = '<div class="agnstk-hello-block">';
        $html .= '<h2>' . esc_html($message) . '</h2>';
        $html .= '<p>This greeting comes from <strong>' . esc_html($appName) . '</strong></p>';
        $html .= '<p>Block ID: <code>' . esc_html($this->getId()) . '</code></p>';
        
        if ($this->getShortcode()) {
            $html .= '<p>Shortcode: <code>[' . esc_html($this->getShortcode()) . ']</code></p>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render for shortcode usage (with different styling)
     */
    public function renderShortcode(array $attributes = []): string {
        $app = ConfigService::getApp();
        $appName = $app['name'] ?? 'AGNSTK';
        
        // Get custom message from shortcode attributes
        $message = $attributes['message'] ?? 'Hello, Agnostic World!';
        
        return sprintf(
            '<span class="agnstk-shortcode agnstk-hello">%s <em>(via %s)</em></span>',
            esc_html($message),
            esc_html($appName)
        );
    }
}
