<?php /** @noinspection BypassedPathTraversalProtectionInspection */

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Filesystem\Filesystem;

class ClearAssets
{
    /** @var string|null */
    protected $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path;
    }


    /**
     * Execute the console command.
     *
     * @param Filesystem  $files
     * @param Application $application
     */
    public function handle(Filesystem $files, Application $application)
    {
        $directory = 'assets';

        if ($this->path) {
            $directory .= DIRECTORY_SEPARATOR . str_replace('../', '', $this->path);
        }

        $files->deleteDirectory($directory = $application->getAssetsPath($directory), true);
    }
}
