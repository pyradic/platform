<?php

namespace Pyro\Platform\Webpack;

use Illuminate\Support\Collection;

/**
 * @method \Pyro\Platform\Webpack\WebpackAddonEntry get($name)
 * @method \Pyro\Platform\Webpack\WebpackAddonEntry[] all()
 */
class WebpackAddonEntryCollection extends Collection
{
    /** @var WebpackAddonEntry[] */
    protected $items = [];

    /**
     * @return \Pyro\Platform\Webpack\WebpackAddonEntry
     */
    public function main()
    {
        foreach($this->items as $entry){
            if($entry->isMain()){
                return $entry;
            }
        }
    }

    /**
     * @return \Pyro\Platform\Webpack\WebpackAddonEntryCollection|\Pyro\Platform\Webpack\WebpackAddonEntry
     */
    public function suffixed()
    {
        return $this->filter(function(WebpackAddonEntry $entry){
            return $entry->isSuffixed();
        });
    }

    /**
     * @param $suffix
     *
     * @return \Pyro\Platform\Webpack\WebpackAddonEntry
     */
    public function suffix($suffix)
    {
        foreach($this->items as $entry){
            if($entry->isSuffix($suffix)){
                return $entry;
            }
        }
    }
}
