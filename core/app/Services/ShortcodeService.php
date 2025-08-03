<?php

namespace AGNSTK\Core\Services;

class ShortcodeService {
    protected static $shortcodes = [];
    protected static $initialized = false;

    /**
     * Initialize shortcode service
     */
    public static function initialize(): void {
        if (self::$initialized) {
            return;
        }

        self::registerBlockShortcodes();
        self::$initialized = true;
    }

    /**
     * Register shortcodes from blocks
     */
    protected static function registerBlockShortcodes(): void {
        $blocks = BlockService::getShortcodeBlocks();

        foreach ($blocks as $block) {
            $shortcode = $block->getShortcode();
            if ($shortcode) {
                self::$shortcodes[$shortcode] = $block;
            }
        }
    }

    /**
     * Process shortcodes in content
     */
    public static function processContent(string $content): string {
        self::initialize();

        // Simple shortcode regex: [shortcode] or [shortcode attr="value"]
        $pattern = '/\[([^\]]+)\]/';
        
        return preg_replace_callback($pattern, function($matches) {
            return self::processShortcode($matches[1]);
        }, $content);
    }

    /**
     * Process a single shortcode
     */
    protected static function processShortcode(string $shortcodeText): string {
        // Parse shortcode and attributes
        $parts = explode(' ', trim($shortcodeText), 2);
        $shortcodeName = $parts[0];
        $attributesText = $parts[1] ?? '';

        // Check if shortcode is registered
        if (!isset(self::$shortcodes[$shortcodeName])) {
            return "[{$shortcodeText}]"; // Return original if not found
        }

        $block = self::$shortcodes[$shortcodeName];
        $attributes = self::parseAttributes($attributesText);

        // Use renderShortcode method if available, otherwise use render
        if (method_exists($block, 'renderShortcode')) {
            return $block->renderShortcode($attributes);
        }

        return $block->render($attributes);
    }

    /**
     * Parse shortcode attributes
     */
    protected static function parseAttributes(string $attributesText): array {
        $attributes = [];
        
        if (empty($attributesText)) {
            return $attributes;
        }

        // Enhanced attribute parsing to handle quoted values properly
        preg_match_all('/(\w+)=(?:(["\'])([^"\']*)\2|([^\s]+))/', $attributesText, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $attributes[$match[1]] = $match[3] ?? $match[4];
        }

        return $attributes;
    }

    /**
     * Get all registered shortcodes
     */
    public static function getRegisteredShortcodes(): array {
        self::initialize();
        return array_keys(self::$shortcodes);
    }

    /**
     * Check if a shortcode is registered
     */
    public static function hasShortcode(string $shortcode): bool {
        self::initialize();
        return isset(self::$shortcodes[$shortcode]);
    }
}
