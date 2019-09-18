<?php

namespace Pyradic\Platform\Asset;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Asset extends \Anomaly\Streams\Platform\Asset\Asset
{
    /**
     * Return the script tag for a collection.
     *
     * @param         $collection
     * @param  array $filters
     * @param  array $attributes
     * @return string
     */
    public function script2($collection, array $filters = [], array $attributes = [])
    {

        $output = '';
        if($this->isWebpackEnabled() && in_array('webpack', $filters,true) && array_key_exists($collection, $this->collections)){
            foreach($this->collections[$collection] as $path => $filter){
                if(Str::startsWith($path, ['http','//'])){
                    $attributes[ 'src' ] = $path;
                } else {
                    $attributes[ 'src' ] = $this->asset($collection, $filters);
                }
                $output .= '<script' . $this->html->attributes($attributes) . '></script>';
            }
            return $output;
        }
        $attributes[ 'src' ] = $this->path($collection, $filters);
        return '<script' . $this->html->attributes($attributes) . '></script>';
    }

    /**
     * Return the style tag for a collection.
     *
     * @param         $collection
     * @param  array $filters
     * @param  array $attributes
     * @return string
     */
    public function style2($collection, array $filters = [], array $attributes = [])
    {
        $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];

        $attributes = $attributes + $defaults;

        $output = '';
        if($this->isWebpackEnabled() && in_array('webpack', $filters,true) && array_key_exists($collection, $this->collections)){
            foreach($this->collections[$collection] as $path => $filter){
                if(Str::startsWith($path, ['http','//'])){
                    $attributes[ 'href' ] = $path;
                } else {
                    $attributes[ 'href' ] = $this->asset($collection, $filters);
                }
                $output .= '<link' . $this->html->attributes($attributes) . '>';
            }
            return $output;
        }
        $attributes[ 'href' ] = $this->asset($collection, $filters);
        return '<link' . $this->html->attributes($attributes) . '>';
    }

    public function add($collection, $file, array $filters = [], $internal = false)
    {
        $webpack = $this->getWebpackPath($filters);
        if($webpack === true){
            return $this;
        }
        if($webpack === false) {
            return parent::add($collection, $file, $filters, $internal);
        }

//        foreach($webpack as $asset) {
//            parent::add($collection, $asset, $filters, $internal);
//        }
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

    private static function sample($k)
    {
        return [ $k => [ 'scripts' => [], 'styles' => [] ], ];
    }

//    /**
//     * Return the script tag for a collection.
//     *
//     * @param         $collection
//     * @param array   $filters
//     * @param array   $attributes
//     * @return string
//     */
//    public function script($collection, array $filters = [], array $attributes = [])
//    {
//        $webpack = $this->getWebpackPath($filters, 'scripts');
//        if ($webpack === true) {
//            return '';
//        }
//        if ($webpack === false) {
//            $attributes[ 'src' ] = $this->path($collection, $filters);
//        } else {
//            $attributes[ 'src' ] = $webpack;
//        }
//        return '<script' . $this->html->attributes($attributes) . '></script>';
//    }
//
//    /**
//     * Return the style tag for a collection.
//     *
//     * @param         $collection
//     * @param array   $filters
//     * @param array   $attributes
//     * @return string
//     */
//    public function style($collection, array $filters = [], array $attributes = [])
//    {
//        $defaults = [ 'media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet' ];
//
//        $attributes = $attributes + $defaults;
//
//        $webpack = $this->getWebpackPath($filters, 'styles');
//        if ($webpack === true) {
//            return '';
//        }
//        if ($webpack === false) {
//            $attributes[ 'href' ] = $this->asset($collection, $filters);
//        } else {
//            $attributes[ 'href' ] = $webpack;
//        }
//        return '<link' . $this->html->attributes($attributes) . '>';
//    }

}
