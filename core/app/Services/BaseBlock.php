<?php

namespace AGNSTK\Core\Services;

abstract class BaseBlock {
    protected string $id;
    protected array $config;

    public function __construct(string $id, array $config) {
        $this->id = $id;
        $this->config = $config;
    }

    /**
     * Get the block ID
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * Get the block configuration
     */
    public function getConfig(): array {
        return $this->config;
    }

    /**
     * Get a specific config value
     */
    public function getConfigValue(string $key, $default = null) {
        return $this->dataGet($this->config, $key, $default);
    }
    
    /**
     * Simple helper for dot notation array access (standalone fallback for data_get)
     */
    private function dataGet($target, $key, $default = null) {
        if (function_exists('data_get')) {
            return data_get($target, $key, $default);
        }
        
        if (is_null($key)) {
            return $target;
        }
        
        $key = is_array($key) ? $key : explode('.', $key);
        
        foreach ($key as $segment) {
            if (is_array($target) && array_key_exists($segment, $target)) {
                $target = $target[$segment];
            } else {
                return $default;
            }
        }
        
        return $target;
    }

    /**
     * Get the block title
     */
    public function getTitle(): string {
        return $this->getConfigValue('title', ucfirst($this->id));
    }

    /**
     * Get the block description
     */
    public function getDescription(): string {
        return $this->getConfigValue('description', '');
    }

    /**
     * Get the block shortcode
     */
    public function getShortcode(): ?string {
        return $this->getConfigValue('shortcode');
    }

    /**
     * Check if the block is enabled
     */
    public function isEnabled(): bool {
        return $this->getConfigValue('enabled', true);
    }

    /**
     * Render the block content - must be implemented by concrete blocks
     */
    abstract public function render(array $attributes = []): string;

    /**
     * Get block metadata for admin interfaces
     */
    public function getMetadata(): array {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'shortcode' => $this->getShortcode(),
            'enabled' => $this->isEnabled()
        ];
    }
}
