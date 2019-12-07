<?php

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Asset\Asset;
use ArrayAccess;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Laradic\Support\Dot;
use Pyro\Platform\Event\PlatformWillRender;
use Pyro\Webpack\Command\RenderJSON;
use Pyro\Webpack\Webpack;

class Platform implements ArrayAccess
{
    /** @var \Pyro\Webpack\Webpack */
    protected $webpack;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Laradic\Support\Dot */
    protected $data;

    /** @var \Laradic\Support\Dot */
    protected $config;

    /** @var \Laradic\Support\Dot */
    protected $global;

    /** @var \Laradic\Support\Dot */
    protected $root;

    /** @var bool */
    protected $preventBootstrap = false;

    /** @var \Illuminate\Support\Collection|\Pyro\Webpack\Package\EntryCollection[] */
    protected $entries;

    /** @var \Collective\Html\HtmlBuilder */
    protected $html;

    public function __construct(
        Application $app,
        Webpack $webpack,
        HtmlBuilder $html
    )
    {
        $this->app     = $app;
        $this->webpack = $webpack;
        $this->html    = $html;

        $this->config = new PlatformConfig();
        $this->config->setDefaults($app);

        $this->entries = new Collection();
        $this->data    = new Dot([]);
        $this->global  = new Dot([]);
        $this->root    = new Dot([]);
    }

    public function addWebpackEntry($name, $suffix = null)
    {
        $this->webpack->enableEntry($name, $suffix);
        return $this;
    }

    public function preventBootstrap($value = true)
    {
        $this->preventBootstrap = $value;
        return $this;
    }

    public function shouldPreventBootstrap()
    {
        return $this->preventBootstrap || config('platform.frontend.bootstrap') === false;
    }

    public function shouldntPreventBootstrap()
    {
        return ! $this->shouldPreventBootstrap();
    }

    public function render()
    {
        event(new PlatformWillRender($this));
        $lines = [//formatter:off
            '<!-- GLOBAL -->',$this->renderGlobal(),PHP_EOL,
            '<!-- CONFIG -->',$this->renderConfig(),PHP_EOL,
            '<!-- DATA -->',$this->renderData(),PHP_EOL
        ];//formatter:on
        return implode(PHP_EOL, $lines);
    }

    public function renderToFile($path, $key = null)
    {
        $data = Collection::make([
            'global' => $this->global,
            'root'   => $this->root,
            'data'   => $this->data,
            'config' => $this->config,
        ]);
        if($key){
            $data = $data->get($key);
        }
        $json = $data->toJson();
        $path = path_is_relative($path) ? base_path($path) : $path;
        file_put_contents($path, $json);
        return $path;
    }

    protected function renderGlobal()
    {
        return dispatch_now(RenderJSON::global($this->global)->assignByKey());
    }

    protected function renderRoot()
    {
        return dispatch_now(RenderJSON::namespace($this->root)->assignByKey());
    }

    protected function renderData()
    {
        return dispatch_now(RenderJSON::namespace($this->data, 'data')->assignByKey());
    }

    protected function renderConfig()
    {
        return dispatch_now(RenderJSON::namespace($this->config, 'config'));
    }

    public function set($key, $value = null)
    {
        $this->data->set($key, $value);
        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->data->get($key, $default);
    }

    public function has($key)
    {
        return $this->data->has($key);
    }

    /**
     * @param array|string|Dot $key
     * @param array|Dot        $value
     *
     * @return $this
     */
    public function merge($key, $value = null)
    {
        $this->data->merge($key, $value);
        return $this;
    }

    public function getAjaxData()
    {
        $assets  = resolve(Asset::class);
        $scripts = $assets->getInCollection('scripts.js')->values()->sortBy('index');
        $styles  = $assets->getInCollection('styles.css')->values()->sortBy('index');
        return [ 'assets' => compact('scripts', 'styles') ];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function global()
    {
        return $this->global;
    }

    public function root()
    {
        return $this->root;
    }

    public function config()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        if ( ! $config instanceof PlatformConfig) {
            $config = new PlatformConfig(Collection::wrap($config)->toArray());
        }
        $this->config = $config;
        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->data->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->data->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->data->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->data->forget($offset);
    }
}
