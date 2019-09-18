<?php

namespace Pyradic\Platform\Console;

use Illuminate\Console\Command;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeCollection;

class AddonListCommand extends Command
{
    protected $signature = 'addon:list {--installed} {--uninstalled} {--enabled} {--disabled} {--module} {--extension} {--theme} {--fields-type}';

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
    public function handle(AddonCollection $addons, ModuleCollection $modules, ExtensionCollection $extensions, FieldTypeCollection $fieldTypes)
    {
        $rows = [];
        foreach ($modules as $module) {
            $rows[] = [
                $module->isInstalled() ? '<fg=green>I</>' : '<fg=red>-</>',
                $module->getNamespace(),
                $module->isEnabled() ? '<fg=green>yes</>' : '<fg=yellow>no</>',
            ];
        }
        $this->table(['i', 'namespace', 'enabled'], $rows);

        $rows = [];
        foreach ($extensions as $extension) {
            $rows[] = [
                $extension->isInstalled() ? '<fg=green>I</>' : '<fg=red>-</>',
                $extension->getNamespace(),
                $extension->isEnabled() ? '<fg=green>yes</>' : '<fg=yellow>no</>',
            ];
        }
        $this->table(['i', 'namespace', 'enabled'], $rows);


        $rows = [];
        foreach ($fieldTypes as $field) {
            $rows[] = [
                $field->getNamespace(),
            ];
        }
        $this->table([ 'namespace' ], $rows);

        foreach ($addons as $addon) {
            $rows[] = [
                $addon->getNamespace(),
                $addon->getType(),
            ];
        }
    }
}
