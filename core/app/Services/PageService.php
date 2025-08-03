<?php

namespace AGNSTK\Core\Services;

class PageService {
    /**
     * Get all pages from configuration
     */
    public static function getPages(): array {
        return ConfigService::getPages();
    }

    /**
     * Get a specific page configuration
     */
    public static function getPage(string $pageId): ?array {
        return ConfigService::getPage($pageId);
    }

    /**
     * Render a page with its blocks
     */
    public static function renderPage(string $pageId): string {
        $pageConfig = self::getPage($pageId);
        
        if (!$pageConfig || !($pageConfig['enabled'] ?? true)) {
            return self::render404();
        }

        $title = $pageConfig['title'] ?? ucfirst($pageId);
        $blocks = $pageConfig['blocks'] ?? [];

        $html = '<div class="agnstk-page agnstk-page-' . esc_html($pageId) . '">';
        $html .= '<h1>' . esc_html($title) . '</h1>';

        foreach ($blocks as $blockId) {
            $html .= '<div class="agnstk-page-block">';
            $html .= BlockService::renderBlock($blockId);
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Get page metadata for routing
     */
    public static function getPageMetadata(): array {
        $pages = self::getPages();
        $routes = ConfigService::getRoutes();
        $metadata = [];

        foreach ($pages as $pageId => $config) {
            if ($config['enabled'] ?? true) {
                $metadata[$pageId] = [
                    'id' => $pageId,
                    'title' => $config['title'] ?? ucfirst($pageId),
                    'slug' => $config['slug'] ?? $pageId,
                    'route' => $routes['public'][$pageId] ?? "/{$pageId}",
                    'blocks' => $config['blocks'] ?? []
                ];
            }
        }

        return $metadata;
    }

    /**
     * Render 404 page
     */
    protected static function render404(): string {
        $app = ConfigService::getApp();
        $appName = $app['name'] ?? 'AGNSTK';

        return '<div class="agnstk-404">
                    <h1>Page Not Found</h1>
                    <p>The requested page could not be found in ' . esc_html($appName) . '.</p>
                </div>';
    }
}
