<?php

namespace Pyro\Platform\Routing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use Laradic\Support\Traits\ArrayableProperties;
use Laradic\Support\Traits\ArrayAccessibleProperties;
use Pyro\Platform\Ui\Traits\HasClassAttribute;
use Pyro\Platform\Ui\Traits\HasHtmlAttributes;

class Breadcrumb implements Arrayable, \ArrayAccess
{
    use ArrayAccessibleProperties;
    use ArrayableProperties;
    use Macroable;
    use HasHtmlAttributes;
    use HasClassAttribute;

    /** @var string */
    protected $key;
    /** @var array */
    protected $route;
    /** @var \Anomaly\Streams\Platform\Addon\Addon */
    protected $addon;
    /** @var string */
    protected $parent;
    /** @var string */
    protected $title;
    /** @var string */
    protected $url;

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getAddon()
    {
        return $this->addon;
    }

    public function setAddon($addon)
    {
        $this->addon = $addon;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }


}
