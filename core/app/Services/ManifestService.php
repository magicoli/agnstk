<?php

namespace AGNSTK\Core\Services;

class ManifestService {
    /**
     * Generate web app manifest JSON from current config
     */
    public static function generateManifest(): array {
        $config = ConfigService::getConfig();
        $site = $config['site'] ?? [];
        $app = $config['app'] ?? [];
        
        $siteName = $site['name'] ?? $app['name'] ?? 'AGNSTK';
        $siteSlogan = $site['slogan'] ?? $app['description'] ?? '';
        $themeColor = $site['theme_color'] ?? '#2563eb';
        $icons = $site['icons'] ?? [];
        
        $manifest = [
            'name' => $siteName,
            'short_name' => self::getShortName($siteName),
            'description' => $siteSlogan,
            'start_url' => UrlService::to('/'),
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => $themeColor,
            'icons' => []
        ];
        
        // Add icons if they exist
        if (isset($icons['android_chrome_192'])) {
            $manifest['icons'][] = [
                'src' => UrlService::to($icons['android_chrome_192']),
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ];
        }
        
        if (isset($icons['android_chrome_512'])) {
            $manifest['icons'][] = [
                'src' => UrlService::to($icons['android_chrome_512']),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ];
        }
        
        return $manifest;
    }
    
    /**
     * Generate and serve manifest.json
     */
    public static function serveManifest(): void {
        header('Content-Type: application/json');
        echo json_encode(self::generateManifest(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    
    /**
     * Get a short name from the full site name
     */
    protected static function getShortName(string $fullName): string {
        // Remove common words and keep initials or first word
        $name = preg_replace('/\s*-\s*.*$/', '', $fullName); // Remove everything after dash
        $words = explode(' ', $name);
        
        if (count($words) > 1) {
            // Use initials for multi-word names
            $initials = '';
            foreach ($words as $word) {
                if (strlen($word) > 0) {
                    $initials .= strtoupper($word[0]);
                }
            }
            return $initials;
        }
        
        // Use first word, max 12 chars
        return substr($words[0], 0, 12);
    }
}
