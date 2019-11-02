<?php

namespace Pyro\Platform\Webpack;

use Illuminate\Support\Collection;


class WebpackAddonCollection extends Collection
{
    /** @var \Pyro\Platform\Webpack\WebpackAddon[] */
    protected $items = [];

    /**
     * @param $namespace
     *
     * @return \Pyro\Platform\Webpack\WebpackAddon
     */
    public function findByStreamNamespace($namespace)
    {
        foreach($this->items as $item){
            if($item->isStreamNamespace($namespace)){
                return $item;
            }
        }
        return null;
    }

    /**
     * @param $name
     *
     * @return \Pyro\Platform\Webpack\WebpackAddon
     */
    public function findByName($name)
    {
        return $this->firstWhere('name', $name);
    }

    /**
     * @return static
     */
    public function streamAddons()
    {
        return $this->filter->isStreamAddon();
    }

    /** @return \Pyro\Platform\Webpack\WebpackAddon */
    public function findByComposerName($name)
    {
        return $this->firstWhere('composerName', $name);
    }

    public function toBase()
    {
        return new Collection($this->items);
    }
}
