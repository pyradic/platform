<?php

namespace Pyro\Platform\Console;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeCollection;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Addon\Theme\ThemeCollection;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddonListCommand extends Command
{
    protected $signature = 'addon:list
                                      {search?}
                                      {--i|installed} {--u|uninstalled}
                                      {--E|enabled} {--D|disabled}
                                      {--m|modules} {--e|extensions} {--f|fields}  {--t|themes}';

    public function handle(AddonCollection $addons)
    {
        if ($this->option('modules')) {
            $this->listModules($addons->modules);
        }
        if ($this->option('extensions')) {
            $this->listExtensions($addons->extensions);
        }
        if ($this->option('fields')) {
            $this->listFields($addons->field_type);
        }
        if ($this->option('themes')) {
            $this->listThemes($addons->themes);
        }

        if (
            ! $this->option('modules') &&
            ! $this->option('extensions') &&
            ! $this->option('fields') &&
            ! $this->option('themes')
        ) {
            $this->listModules($addons->modules);
            $this->listExtensions($addons->extensions);
            $this->listFields($addons->field_type);
            $this->listThemes($addons->themes);
        }
    }

    protected function listThemes(ThemeCollection $themes)
    {
        $this->getOutput()->title('Themes');
        $rows = [];
        $themes = $this->filterSearch($themes);

        foreach ($themes->all() as $theme) {
            $rows[] = [
                $theme->getNamespace(),
            ];
        }
        $this->table([ 'namespace' ], $rows);
    }

    protected function listFields(FieldTypeCollection $fields)
    {
        $this->getOutput()->title('Fields');
        $rows = [];
        $fields = $this->filterSearch($fields);

        foreach ($fields->all() as $field) {
            $rows[] = [
                $field->getNamespace(),
            ];
        }
        $this->table([ 'namespace' ], $rows);
    }

    protected function listExtensions(ExtensionCollection $extensions)
    {
        $this->getOutput()->title('Extensions');
        foreach ([ 'installed', 'uninstalled', 'enabled', 'disabled' ] as $state) {
            if ($this->option($state)) {
                $extensions = $extensions->{$state}();
            }
        }

            $extensions = $this->filterSearch($extensions);

        $rows = [];
        foreach ($extensions as $extension) {
            $rows[] = [
                $extension->isInstalled() ? "<fg=green>{$extension->getNamespace()}</>" : "<fg=red>{$extension->getNamespace()}</>",
                ($extension->isEnabled() ? '<fg=green>yes</>' : '<fg=yellow>-</>'),
            ];
        }
        $this->table([ 'namespace', 'enabled' ], $rows);
    }

    protected function listModules(ModuleCollection $modules)
    {
        $this->getOutput()->title('Modules');
        foreach ([ 'installed', 'uninstalled', 'enabled', 'disabled' ] as $state) {
            if ($this->option($state)) {
                $modules = $modules->{$state}();
            }
        }

        $modules = $this->filterSearch($modules);

        $rows = [];
        foreach ($modules as $module) {
            $rows[] = [
                $module->isInstalled() ? "<fg=green>{$module->getNamespace()}</>" : "<fg=red>{$module->getNamespace()}</>",
                ($module->isEnabled() ? '<fg=green>yes</>' : '<fg=yellow>-</>'),
            ];
        }
        $this->table([ 'namespace', 'enabled' ], $rows);
    }

    /**
     * @param \Anomaly\Streams\Platform\Addon\AddonCollection $addons
     *
     * @return \Anomaly\Streams\Platform\Addon\Addon[]|\Anomaly\Streams\Platform\Addon\AddonCollection
     */
    protected function filterSearch(AddonCollection $addons)
    {
        if($search = $this->argument('search')) {
            return $addons->filter(function (Addon $addon) use ($search) {
                return Str::is("*{$search}*", $addon->getNamespace());
            });
        }
        return $addons;
    }
}
