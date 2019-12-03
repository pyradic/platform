<?php

namespace Pyro\Platform\Ui\ControlPanel;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\NamespacedItemResolver;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class ControlPanelStructure extends Collection
{
    public function getActiveNavigation()
    {
        return $this->firstWhere('active', true);
    }

    public function getActiveSection()
    {
        if ($navigation = $this->getActiveNavigation()) {
            return $navigation->get('children')->firstWhere('active', true);
        }
    }

    /**
     * @param mixed $key
     * @param null  $default
     *
     * @return Collection
     */
    public function getNavigation($key, $default = null)
    {
        [ $nav, $sectionKey, $buttonKey ] = resolve(NamespacedItemResolver::class)->parseKey($key);
        return $this->firstWhere('key', $nav);
    }

    /**
     * @param $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSection($key)
    {
        try {
            [ $nav, $sectionKey, $buttonKey ] = resolve(NamespacedItemResolver::class)->parseKey($key);

            $navigation= $this->getNavigation($key);
            if($navigation === null){
                return collect();
            }
            return $navigation->get('children')->firstWhere('key', $nav . '::' . $sectionKey);

        } catch(\Exception $e){

            return collect();
        }
    }

    /**
     * @param $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function getButton($key)
    {
        [ $nav, $sectionKey, $buttonKey ] = resolve(NamespacedItemResolver::class)->parseKey($key);
        return $this->getSection($key)->get('children')->firstWhere('key', $key);
    }

    /**
     * @param string|string[]|array $keys
     *
     * @return $this
     */
    public function translate($keys)
    {
        $this->translateAll($this, $keys);
        return $this;
    }

    protected function translateAll(Collection $all, $keys)
    {
        $all->each(function(Collection $item) use ($keys){
            foreach(Arr::wrap($keys) as $key){
                $item->put($key, trans($item->get($key, '')));
            }
            if($item->has('children')){
                $this->translateAll($item->get('children',collect()), $keys);
            }
        });

        return $all;
    }
}
