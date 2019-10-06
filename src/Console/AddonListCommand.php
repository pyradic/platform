<?php

namespace Pyradic\Platform\Console;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeCollection;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Addon\Theme\ThemeCollection;
use Illuminate\Console\Command;

class AddonListCommand extends Command
{
    protected $signature = 'addon:list {--installed} {--uninstalled} {--enabled} {--disabled} {--modules} {--extensions} {--fields}  {--themes}';

    /**
     * handle method
     *
     * @param \Anomaly\Streams\Platform\Addon\AddonCollection|\Anomaly\Streams\Platform\Addon\Addon[]                             $addons
     *
     * @param \Anomaly\Streams\Platform\Addon\Module\ModuleCollection|\Anomaly\Streams\Platform\Addon\Module\Module[]             $modules
     * @param \Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection|\Anomaly\Streams\Platform\Addon\Extension\Extension[] $extensions
     * @param \Anomaly\Streams\Platform\Addon\FieldType\FieldTypeCollection|\Anomaly\Streams\Platform\Addon\FieldType\FieldType[] $fieldTypes
     *
     * @return void
     */
    public function handle(AddonCollection $addons, ModuleCollection $modules, ExtensionCollection $extensions, FieldTypeCollection $fields, ThemeCollection $themes)
    {
        if ($this->option('modules')) {
            $this->listModules($modules);
        }
        if ($this->option('extensions')) {
            $this->listExtensions($extensions);
        }
        if ($this->option('fields')) {
            $this->listFields($fields);
        }
        if ($this->option('themes')) {
            $this->listThemes($themes);
        }

        if (
            ! $this->option('modules') &&
            ! $this->option('extensions') &&
            ! $this->option('fields') &&
            ! $this->option('themes')
        ) {
            $this->listModules($modules);
            $this->listExtensions($extensions);
            $this->listFields($fields);
            $this->listThemes($themes);
        }
    }

    protected function listThemes(ThemeCollection $themes)
    {
        $rows = [];
        foreach ($themes as $theme) {
            $rows[] = [
                $theme->getNamespace(),
            ];
        }
        $this->table([ 'namespace' ], $rows);
    }

    protected function listFields(FieldTypeCollection $fields)
    {
        $rows = [];
        foreach ($fields as $field) {
            $rows[] = [
                $field->getNamespace(),
            ];
        }
        $this->table([ 'namespace' ], $rows);
    }

    protected function listExtensions(ExtensionCollection $extensions)
    {
        foreach ([ 'installed', 'uninstalled', 'enabled', 'disabled' ] as $state) {
            if ($this->option($state)) {
                $extensions = $extensions->{$state}();
            }
        }

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
        foreach ([ 'installed', 'uninstalled', 'enabled', 'disabled' ] as $state) {
            if ($this->option($state)) {
                $modules = $modules->{$state}();
            }
        }

        $rows = [];
        foreach ($modules as $module) {
            $rows[] = [
                $module->isInstalled() ? "<fg=green>{$module->getNamespace()}</>" : "<fg=red>{$module->getNamespace()}</>",
                ($module->isEnabled() ? '<fg=green>yes</>' : '<fg=yellow>-</>'),
            ];
        }
        $this->table([ 'namespace', 'enabled' ], $rows);
    }
}
