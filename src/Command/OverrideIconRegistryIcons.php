<?php

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Ui\Icon\IconRegistry;
use Illuminate\Filesystem\Filesystem;
use Laradic\Support\FS;

class OverrideIconRegistryIcons
{
    /** @var string */
    protected $outputFilePath;

    /** @var string */
    protected $set;

    public function __construct(string $outputPath = null, string $set = null)
    {
        $this->outputFilePath = $outputPath ?? config('platform.icons.registry_override.output_path', app_storage_path());
        $this->set            = $set ?? config('platform.icons.registry_override.set', 'mdi');
    }

    /** @noinspection PhpIncludeInspection */
    public function handle(IconRegistry $registry,Filesystem $fs)
    {
        if(!$fs->exists($this->outputFilePath)){ //  || $fs->lastModified($this->outputFilePath)
            dispatch_now(new GenerateIconRegistryOverrides($this->outputFilePath, $this->set));
        }

        $icons=$registry->getIcons();
        foreach(require $this->outputFilePath as $key){
            $icons[$key] = 'mdi mdi-'.$key;
        }
        $registry->setIcons($icons);
    }

}
