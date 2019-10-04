<?php

namespace Pyradic\Platform\Asset;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Asset extends \Anomaly\Streams\Platform\Asset\Asset
{
    public function add($collection, $file, array $filters = [], $internal = false)
    {
        $webpack = $this->getWebpackPath($filters);
        if($webpack === true){
            return $this;
        }
        if($webpack === false) {
            return parent::add($collection, $file, $filters, $internal);
        }
        return $this;
    }


    protected $webpackAddedBundles = [
        'scripts' => [],
        'styles'  => [],
    ];

    protected function getWebpackPath(&$filters)
    {
        if ($this->isWebpackEnabled()) {
            $webpack = $this->pullWebpackFilter($filters);
            if ($webpack !== null) {
                $bundle  = $webpack[ 'bundle' ];
                $type    = $webpack[ 'type' ];
                $bundles = $this->getWebpackBundles();
                if (array_key_exists($bundle, $bundles)) {
                    $assets = Arr::wrap($bundles[ $bundle ][ $type ]);
                    if (empty($assets)) {
                        return true;
                    }
                    if (in_array($bundle, $this->webpackAddedBundles[ $type ], false)) {
                        return true;
                    }
                    $this->webpackAddedBundles[ $type ][] = $bundle;
                    return $assets;
                }
            }
        }
        return false;
    }

    protected function pullWebpackFilter(&$filters)
    {
        $webpack = null;
        foreach ($filters as $key => $value) {

            if (is_string($value) && preg_match( '/^webpack:.*:(scripts|styles)$/',$value) > 0) {
                $value = Str::replaceFirst('webpack:', '', $value);
                list($bundle, $type) = explode(':', $value);
                $webpack[ 'bundle' ] = $bundle;
                $webpack[ 'type' ]   = $type;
                unset($filters[ $key ]);
            }
        }
        return $webpack;
    }

    /**
     * @return array = static::sample()
     */
    public function getWebpackBundles()
    {
        return config('webpack.bundles');
    }

    public function isWebpackEnabled()
    {
        return config('webpack.enabled', false) === true;
    }
}
