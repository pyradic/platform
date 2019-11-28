<?php

namespace Pyro\Platform;

use ArrayAccess;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Laradic\Support\Dot;
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
        $this->data    = new Dot([
        ]);
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
        return $this->renderConfig() . PHP_EOL . $this->renderData();
    }

    public function renderData()
    {
//        $json = $this->data->toJson();
//        $json = str_replace("'", "\\'", $json);
        $js   = "window['{$this->webpack->getNamespace()}'].data = window['{$this->webpack->getNamespace()}'].data || {};";// = JSON.parse('{$json}');";
        foreach($this->data->keys() as $key){
            $json = $this->data->toJson($key);
            $json = str_replace("\\", "\\\\", $json);
            $json = str_replace("'", "\\'", $json);
            $js   .= "\nwindow['{$this->webpack->getNamespace()}'].data['{$key}'] = JSON.parse('{$json}');";
        }
        return "<script> {$js} </script>";
    }

    public function renderConfig()
    {
        $json = $this->config->toJson();
        $json = str_replace("'", "\\'", $json);
        $js   = "window['{$this->webpack->getNamespace()}'].config = JSON.parse('{$json}');";
        return "<script> {$js} </script>";
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
    public function merge($key, $value = [])
    {
        $this->data->merge($key, $value);
        return $this;
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

    public function config()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
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
