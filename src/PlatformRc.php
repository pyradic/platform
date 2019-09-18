<?php

namespace Pyradic\Platform;

use Laradic\Support\FS;
use Anomaly\Streams\Platform\Addon\Addon;

/**
 * @property-read \Illuminate\Support\Collection $skipInstall
 * @property-read \Illuminate\Support\Collection $skipSeed
 */
class PlatformRc
{
    /** @var \Illuminate\Support\Collection */
    protected $data;

    protected $filePath;

    public function __construct(array $data = [])
    {
        $this->filePath = base_path('.platformrc');
        $this->init($data);
    }

    protected function init(array $data = [])
    {
        $this->data = collect($data);
        $this->data->put('skipInstall', collect($this->data->get('skipInstall', [])));
        $this->data->put('skipSeed', collect($this->data->get('skipSeed', [])));
        return $this;
    }

    /**
     * shouldSkipInstall method
     *
     * @param \Anomaly\Streams\Platform\Addon\Addon|string $addon
     *
     * @return boolean
     */
    public function shouldSkipInstall($addon)
    {
        $addon = $addon instanceof Addon ? $addon->getNamespace() : $addon;
        return $this->skipInstall->contains($addon);
    }

    /**
     * shouldSkipInstall method
     *
     * @param \Anomaly\Streams\Platform\Addon\Addon|string $addon
     *
     * @return boolean
     */
    public function shouldSkipSeed($addon)
    {
        $addon = $addon instanceof Addon ? $addon->getNamespace() : $addon;
        return $this->skipSeed->contains($addon);
    }

    public function __get($name)
    {
        return data_get($this->data, $name);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function saveTo($path)
    {
        FS::put($path, $this->data->toJson());
        return $this;
    }

    public function save()
    {
        $this->saveTo($this->filePath);
        return $this;
    }

    public function fileExists()
    {
        return FS::exists($this->filePath);
    }

    public function loadFrom($path)
    {
        $this->init(json_decode(FS::get($path), true));
        return $this;
    }

    public function load()
    {
        $this->loadFrom($this->filePath);
    }
}
