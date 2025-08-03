<?php

namespace AGNSTK\Core\Services;

class SecurityService {
    /**
     * Ensure API keys exist in config, generate if missing
     */
    public static function ensureApiKeys(): array {
        $config = ConfigService::getConfig();
        $apiKeys = $config['api']['keys'] ?? [];
        
        $keysToGenerate = ['admin', 'read_only'];
        $newKeys = [];
        $updated = false;
        
        foreach ($keysToGenerate as $keyType) {
            if (!isset($apiKeys[$keyType]) || empty($apiKeys[$keyType])) {
                $newKeys[$keyType] = self::generateSecureKey();
                $updated = true;
            } else {
                $newKeys[$keyType] = $apiKeys[$keyType];
            }
        }
        
        if ($updated) {
            self::saveApiKeysToConfig($newKeys);
        }
        
        return $newKeys;
    }
    
    /**
     * Generate a secure random API key
     */
    protected static function generateSecureKey(): string {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Save API keys to config.json
     */
    protected static function saveApiKeysToConfig(array $keys): void {
        $configPath = __DIR__ . '/../../../config.json';
        
        // Load existing config or create empty array
        $userConfig = [];
        if (file_exists($configPath)) {
            $content = file_get_contents($configPath);
            if ($content !== false) {
                $userConfig = json_decode($content, true) ?? [];
            }
        }
        
        // Update API keys in user config
        if (!isset($userConfig['api'])) {
            $userConfig['api'] = [];
        }
        $userConfig['api']['keys'] = $keys;
        
        // Save back to file
        $json = json_encode($userConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($configPath, $json);
        
        // Clear config cache to force reload
        ConfigService::clearCache();
    }
    
    /**
     * Validate an API key
     */
    public static function validateApiKey(string $key, string $requiredLevel = 'read_only'): bool {
        $keys = self::ensureApiKeys();
        
        // Admin key can do everything
        if ($key === $keys['admin']) {
            return true;
        }
        
        // Read-only key for read operations
        if ($requiredLevel === 'read_only' && $key === $keys['read_only']) {
            return true;
        }
        
        return false;
    }
}
