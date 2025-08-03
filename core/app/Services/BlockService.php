<?php

namespace AGNSTK\Core\Services;

use AGNSTK\Core\Blocks\HelloBlock;

class BlockService {
    protected static $blocks = [];
    protected static $initialized = false;

    /**
     * Initialize the block service
     */
    public static function initialize(): void {
        if (self::$initialized) {
            return;
        }

        self::registerDefaultBlocks();
        self::$initialized = true;
    }

    /**
     * Register default blocks from configuration
     */
    protected static function registerDefaultBlocks(): void {
        $blocksConfig = ConfigService::getBlocks();

        foreach ($blocksConfig as $blockId => $config) {
            if (!isset($config['enabled']) || $config['enabled']) {
                self::registerBlock($blockId, $config);
            }
        }
    }

    /**
     * Register a block
     */
    public static function registerBlock(string $blockId, array $config): void {
        // Map block IDs to their classes
        $blockClass = self::getBlockClass($blockId);
        
        if (class_exists($blockClass)) {
            self::$blocks[$blockId] = new $blockClass($blockId, $config);
        }
    }

    /**
     * Get the class name for a block ID
     */
    protected static function getBlockClass(string $blockId): string {
        // Convert block ID to class name (e.g., 'hello' -> 'HelloBlock')
        $className = ucfirst($blockId) . 'Block';
        return "AGNSTK\\Core\\Blocks\\{$className}";
    }

    /**
     * Get a specific block
     */
    public static function getBlock(string $blockId): ?BaseBlock {
        self::initialize();
        return self::$blocks[$blockId] ?? null;
    }

    /**
     * Get all registered blocks
     */
    public static function getAllBlocks(): array {
        self::initialize();
        return self::$blocks;
    }

    /**
     * Get all enabled blocks
     */
    public static function getEnabledBlocks(): array {
        self::initialize();
        return array_filter(self::$blocks, fn($block) => $block->isEnabled());
    }

    /**
     * Render a specific block
     */
    public static function renderBlock(string $blockId, array $attributes = []): string {
        $block = self::getBlock($blockId);
        
        if (!$block || !$block->isEnabled()) {
            return '';
        }

        return $block->render($attributes);
    }

    /**
     * Get all blocks with their metadata for admin interfaces
     */
    public static function getBlocksMetadata(): array {
        self::initialize();
        return array_map(fn($block) => $block->getMetadata(), self::$blocks);
    }

    /**
     * Get blocks that have shortcodes
     */
    public static function getShortcodeBlocks(): array {
        self::initialize();
        return array_filter(self::$blocks, fn($block) => $block->getShortcode() !== null);
    }
}
