<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\File;

final class PluginService
{
    private string $pluginsPath;
    private SettingsService $settings;

    public function __construct(SettingsService $settings)
    {
        $this->pluginsPath = dirname(base_path()) . '/nw-content/plugins';
        $this->settings = $settings;
    }

    /**
     * Discover all plugins in the plugins directory.
     * Returns an array of plugin metadata.
     */
    public function discoverPlugins(): array
    {
        $plugins = [];

        if (!File::exists($this->pluginsPath)) {
            File::makeDirectory($this->pluginsPath, 0755, true);
            return $plugins;
        }

        $directories = File::directories($this->pluginsPath);

        foreach ($directories as $directory) {
            $jsonPath = $directory . '/plugin.json';
            
            if (File::exists($jsonPath)) {
                $jsonContent = File::get($jsonPath);
                $metadata = json_decode($jsonContent, true);
                
                if (is_array($metadata) && isset($metadata['slug'], $metadata['provider'])) {
                    // Add active state to metadata
                    $metadata['is_active'] = $this->isActive($metadata['slug']);
                    $plugins[$metadata['slug']] = $metadata;
                }
            }
        }

        return $plugins;
    }

    /**
     * Get an array of currently active plugin slugs.
     */
    public function getActivePlugins(): array
    {
        $activePluginsJson = $this->settings->get('active_plugins', '[]');
        return json_decode($activePluginsJson, true) ?? [];
    }

    /**
     * Check if a specific plugin is active.
     */
    public function isActive(string $slug): bool
    {
        return in_array($slug, $this->getActivePlugins());
    }

    /**
     * Activate a plugin by its slug.
     */
    public function activate(string $slug): void
    {
        $active = $this->getActivePlugins();
        
        if (!in_array($slug, $active)) {
            $active[] = $slug;
            $this->settings->set('active_plugins', json_encode($active));
        }
    }

    /**
     * Deactivate a plugin by its slug.
     */
    public function deactivate(string $slug): void
    {
        $active = $this->getActivePlugins();
        
        $key = array_search($slug, $active);
        if ($key !== false) {
            unset($active[$key]);
            $this->settings->set('active_plugins', json_encode(array_values($active)));
        }
    }
}
