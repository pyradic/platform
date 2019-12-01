<?php

namespace Pyro\Platform\Asset;

use Illuminate\Support\Str;

class Asset extends \Anomaly\Streams\Platform\Asset\Asset
{

    public function publishTo($collection, $filename, $filters = [])
    {
        $outputPath = $this->paths->outputPath($collection);
        $path       = path_join(path_get_directory($outputPath), $filename);
        $this->publish($path, $collection, $filters);
        return $path;
    }

    /** @return \Illuminate\Support\Collection */
    public function getInCollection($collection)
    {

        $paths = collect($this->getPaths()->getPaths());
        $index = 0;
        return $this->getCollections()->collect($collection)->mapWithKeys(function ($filters, $filePath) use ($paths, &$index) {
            $asset                = [];
            $asset[ 'namespace' ] = $paths->flip()->first(function ($namespace, $path) use ($filePath) {
                return Str::startsWith($filePath, $path);
            });
            $asset[ 'index' ]     = $index++;
            $asset[ 'dir' ]       = $paths->get($asset[ 'namespace' ]);
            $asset[ 'file' ]      = $filePath;
            $asset[ 'relative' ]  = Str::removeLeft($filePath, $asset[ 'dir' ] . '/');
            $asset[ 'key' ]       = $asset[ 'namespace' ] . '::' . $asset[ 'relative' ];
            $asset[ 'path' ]      = $this->path($filePath, $filters);
            $asset[ 'content' ]   = function () use ($filePath) {
                return $this->content($filePath);
            };
            return [ $filePath => $asset ];
        })->keyBy('key');
    }

    public function has($collection)
    {
        return array_key_exists($collection, $this->collections);
    }

    public function get($collection)
    {
        if ($this->has($collection)) {
            return $this->collections[ $collection ];
        }
        return null;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getCollections()
    {
        return collect($this->collections);
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function getParser()
    {
        return $this->parser;
    }

    public function getFilters()
    {
        return $this->filters;
    }


//
//    public function add($collection, $file, array $filters = [], $internal = false)
//    {
//        $webpack = $this->getWebpackPath($filters);
//        if ($webpack === true) {
//            return $this;
//        }
//        if ($webpack === false) {
//            return parent::add($collection, $file, $filters, $internal);
//        }
//        return $this;
//    }
//
//    public function publicScript($relativePath, $webpackName = null)
//    {
//        $webpackName = Arr::wrap($webpackName);
//        if ($this->getWebpackPath($webpackName)) {
//            return '';
//        }
//        $attributes[ 'src' ] = $this->getPublicAssetUrl($relativePath);
//        return '<script' . $this->html->attributes($attributes) . '></script>';
//    }
//
//    public function publicStyle($relativePath, $webpackName = null)
//    {
//        $webpackName = Arr::wrap($webpackName);
//        if ($this->getWebpackPath($webpackName)) {
//            return '';
//        }
//
//        $attributes = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];
//        $attributes[ 'href' ] = $this->getPublicAssetUrl($relativePath);
//        return '<link' . $this->html->attributes($attributes) . '>';
//    }
//
//    protected function getPublicAssetUrl($relativePath)
//    {
//        // @todo this is workaround for weird bug with radic.dev, need to find a proper solution!
//        $url   = url()->asset($relativePath, request()->isSecure());
//        $after = Str::after($url, $relativePath);
//        if ($after !== '') {
//            $url = str_replace($relativePath . $after, $relativePath, $url);
//        }
//        return $url;
//    }
//
//    protected $webpackAddedBundles = [
//        'scripts' => [],
//        'styles'  => [],
//    ];
//
//    protected function getWebpackPath(&$filters)
//    {
//        if ($this->isWebpackEnabled()) {
//            $webpack = $this->pullWebpackFilter($filters);
//            if ($webpack !== null) {
//                $bundle  = $webpack[ 'bundle' ];
//                $type    = $webpack[ 'type' ];
//                $bundles = $this->getWebpackBundles();
//                if (array_key_exists($bundle, $bundles)) {
//                    $assets = Arr::wrap($bundles[ $bundle ][ $type ]);
//                    if (empty($assets)) {
//                        return true;
//                    }
//                    if (in_array($bundle, $this->webpackAddedBundles[ $type ], false)) {
//                        return true;
//                    }
//                    $this->webpackAddedBundles[ $type ][] = $bundle;
//                    return $assets;
//                }
//            }
//        }
//        return false;
//    }
//
//    protected function pullWebpackFilter(&$filters)
//    {
//        $webpack = null;
//        foreach ($filters as $key => $value) {
//
//            if (is_string($value) && preg_match('/^webpack:.*:(scripts|styles)$/', $value) > 0) {
//                $value = Str::replaceFirst('webpack:', '', $value);
//                list($bundle, $type) = explode(':', $value);
//                $webpack[ 'bundle' ] = $bundle;
//                $webpack[ 'type' ]   = $type;
//                unset($filters[ $key ]);
//            }
//        }
//        return $webpack;
//    }
//
//    /**
//     * @return array = static::sample()
//     */
//    public function getWebpackBundles()
//    {
//        return config('webpack.bundles');
//    }
//
//    public function isWebpackEnabled()
//    {
//        return config('webpack.enabled', false) === true;
//    }
}
