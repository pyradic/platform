<?php

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Ui\Icon\IconRegistry;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateIconRegistryOverrides
{
    /** @var string */
    protected $outputFilePath;

    /** @var string */
    protected $set;

    public function __construct(string $outputPath = null, string $set = null)
    {
        $this->outputFilePath = $outputPath ?? config('platform.icons.registry_overrides.output_path', app_storage_path());
        $this->set            = $set ?? config('platform.icons.registry_overrides.set', 'mdi');
    }

    public function handle(IconRegistry $registry, Filesystem $fs)
    {
        $this->set;
        $setIcons        = config('platform.icons.sets.' . $this->set, []);
        $registeredIcons = $registry->getIcons();
        $found           = [];
        foreach (array_keys($registeredIcons) as $icon) {
            if (
                in_array($icon, $setIcons, true)
                && Str::startsWith($registeredIcons[ $icon ], $this->set) === false
            ) {
                $found[] = $icon;
            }
        }
        $content = "<?php \n return " . var_export($found, true) . ';';

        $fs->ensureDirectory(dirname($this->outputFilePath));
        $fs->put($this->outputFilePath, $content);
    }
}
