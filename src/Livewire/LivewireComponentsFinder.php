<?php

namespace Pyro\Platform\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Livewire\Component;
use ReflectionClass;

class LivewireComponentsFinder extends \Livewire\LivewireComponentsFinder
{
    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Illuminate\Support\Collection */
    protected $paths;

    /** @var \Illuminate\Support\Collection */
    protected $classes;

    public function __construct(Application $app, $manifestPath, $path)
    {
        $this->app     = $app;
        $this->paths   = collect([ $path ]);
        $this->classes = collect();
        parent::__construct($app[ 'files' ], $manifestPath, $path);
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function addClass($class)
    {
        $this->classes[] = $class;
        return $this;
    }

    public function addPath($path)
    {
        $this->paths[] = $path;
        return $this;
    }

    public function find($alias)
    {
        return $this->getManifest()[ $alias ] ?? null;
    }

    public function getManifest()
    {
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        if ( ! file_exists($this->manifestPath)) {
            $this->build();
        }

        return $this->manifest = $this->files->getRequire($this->manifestPath);
    }

    public function build()
    {
        $this->manifest = $this->getClassNames()
            ->mapWithKeys(function ($class) {
                return [ (new $class('dummy-id'))->getName() => $class ];
            })->toArray();

        $this->write($this->manifest);

        return $this;
    }

    protected function write(array $manifest)
    {
        if ( ! is_writable(dirname($this->manifestPath))) {
            throw new \Exception('The ' . dirname($this->manifestPath) . ' directory must be present and writable.');
        }

        $this->files->put($this->manifestPath, '<?php return ' . var_export($manifest, true) . ';', true);
    }

    public function getClassNames()
    {
        $this->classes->map('class_exists');
        $classes = get_declared_classes();
        $classes = collect(array_filter($classes, function (string $class) {
            return is_subclass_of($class, Component::class) &&
                ! (new ReflectionClass($class))->isAbstract();
        }));
        return $this->classes->merge($classes);
    }

}
