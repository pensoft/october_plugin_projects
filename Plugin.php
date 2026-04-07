<?php namespace Pensoft\Projects;

use Backend;
use System\Classes\PluginBase;
use Pensoft\Projects\Components\ProjectsList;
use Pensoft\Projects\Components\ProjectsItem;

/**
 * Projects Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Projects',
            'description' => 'No description provided yet...',
            'author'      => 'Pensoft',
            'icon'        => 'icon-btc'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {

    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return [
            ProjectsList::class => 'projects_list',
            ProjectsItem::class => 'projects_item',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     */
    public function registerPermissions(): array
    {

        return [
            'pensoft.projects.access' => [
                'tab' => 'Projects',
                'label' => 'Manage projects'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     */
    public function registerNavigation(): array
    {
        return [
            'projects' => [
                'label'       => 'Projects',
                'url'         => Backend::url('pensoft/projects/projects'),
                'icon'        => 'icon-btc',
                'permissions' => ['pensoft.projects.*'],
                'order'       => 500,
            ],
        ];
    }
}
