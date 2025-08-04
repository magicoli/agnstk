<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;

class PageService
{
    /**
     * Get page configuration
     */
    public static function getPageConfig(string $pageId): ?array
    {
        $pages = config('app-defaults.pages', []);
        return $pages[$pageId] ?? null;
    }

    /**
     * Get all enabled pages
     */
    public static function getEnabledPages(): array
    {
        $pages = config('app-defaults.pages', []);
        return array_filter($pages, fn($page) => $page['enabled'] ?? false);
    }

    /**
     * Get menu items
     */
    public static function getMenuItems(): array
    {
        $pages = self::getEnabledPages();
        $menuItems = [];

        foreach ($pages as $pageId => $page) {
            if (isset($page['menu']) && ($page['menu']['enabled'] ?? false)) {
                $menuItems[] = [
                    'pageId' => $pageId,
                    'label' => $page['menu']['label'] ?? $page['title'],
                    'url' => $page['slug'],
                    'order' => $page['menu']['order'] ?? 999,
                    'auth_required' => $page['menu']['auth_required'] ?? false,
                ];
            }
        }

        // Sort by order
        usort($menuItems, fn($a, $b) => $a['order'] <=> $b['order']);

        return $menuItems;
    }

    /**
     * Render page content based on its configuration
     */
    public static function renderPageContent(string $pageId): string
    {
        $page = self::getPageConfig($pageId);
        
        if (!$page || !($page['enabled'] ?? false)) {
            return '<div class="alert alert-warning">Page not found or disabled.</div>';
        }

        $contentSource = $page['content_source'] ?? 'view';
        $contentId = $page['content_id'] ?? $pageId;

        switch ($contentSource) {
            case 'readme':
                return self::renderReadmeContent();
                
            case 'block':
                return self::renderBlockContent($contentId);
                
            case 'service':
                return self::renderServiceContent($contentId);
                
            case 'view':
                return self::renderViewContent($contentId);
                
            default:
                return '<div class="alert alert-warning">Unknown content source: ' . $contentSource . '</div>';
        }
    }

    /**
     * Render README.md content as HTML
     */
    private static function renderReadmeContent(): string
    {
        $readmePath = base_path('README.md');
        
        if (!File::exists($readmePath)) {
            return '<div class="alert alert-warning">README.md not found.</div>';
        }

        $markdown = File::get($readmePath);
        $converter = new CommonMarkConverter();
        
        return '<div class="readme-content">' . $converter->convert($markdown) . '</div>';
    }

    /**
     * Render block content
     */
    private static function renderBlockContent(string $blockId): string
    {
        $blocks = config('app-defaults.blocks', []);
        $block = $blocks[$blockId] ?? null;
        
        if (!$block || !($block['enabled'] ?? false)) {
            return '<div class="alert alert-warning">Block "' . $blockId . '" not found or disabled.</div>';
        }

        return '<div class="block-content">' . ($block['content'] ?? '') . '</div>';
    }

    /**
     * Render service content
     */
    private static function renderServiceContent(string $serviceCall): string
    {
        // Parse service@method format
        if (strpos($serviceCall, '@') !== false) {
            [$serviceClass, $method] = explode('@', $serviceCall, 2);
            $fullServiceClass = "App\\Services\\{$serviceClass}";
            
            if (class_exists($fullServiceClass) && method_exists($fullServiceClass, $method)) {
                return app($fullServiceClass)->$method();
            }
        }
        
        return '<div class="alert alert-warning">Service "' . $serviceCall . '" not found.</div>';
    }

    /**
     * Render view content
     */
    private static function renderViewContent(string $viewId): string
    {
        try {
            return view($viewId)->render();
        } catch (\Exception $e) {
            return '<div class="alert alert-warning">View "' . $viewId . '" not found.</div>';
        }
    }
}
