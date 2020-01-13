<?php /** @noinspection UnNecessaryDoubleQuotesInspection */

namespace Pyro\Platform\Component;

use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Anomaly\Streams\Platform\Traits\Hookable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laradic\Support\Traits\ArrayableProperties;
use Laradic\Support\Traits\ArrayAccessibleProperties;
use Pyro\Platform\Ui\TreeNode\NodeTrait;

class Component implements ComponentInterface
{
    use FiresCallbacks;
    use Hookable;
    use ArrayableProperties;
    use ArrayAccessibleProperties;
    use SharesStore;
    use NodeTrait;

    protected $arrayable = [ 'tag', 'attrs', 'props', 'class', 'children' ];

    /** @var string */
    protected $tag;

    /** @var \Pyro\Webpack\Package\Entry */
    protected $entry;

    /** @var Collection|array<string,mixed> */
    protected $attrs;

    /** @var PropCollection|Prop[]|array<string,Prop> */
    protected $props;

    /** @var Collection|string[]|array<string,boolean> */
    protected $class;

    public function __construct(array $data = [])
    {
        $this->setCollectionClass(ComponentCollection::class);
        $this->reset($data);
    }

    public static function create($data = [], ComponentInterface $parent = null)
    {
        $component = new static($data);
        $component->setParent($parent);
        return $component;
    }

    public function reset(array $data = [])
    {
        $data = array_replace_recursive([ 'tag' => null, 'props' => [], 'attrs' => [], 'class' => [], 'children' => [] ], $data);

        $this->tag   = $data[ 'tag' ] ?? $this->tag;
        $this->attrs = new Collection();
        $this->props = new PropCollection();
        $this->class = new Collection();

        $this->class = $data[ 'class' ] ?? $this->class;


        foreach (Arr::wrap($data['props']) as $name => $value) {
            $this->props->set($name, $value);
        }
        foreach (Arr::wrap($data['attrs']) as $name => $value) {
            $this->attrs->put($name, $value);
        }
    }

    public function getProps()
    {
        return $this->props;
    }

    public function render($pretty = false)
    {
        $tag   = Str::kebab($this->tag);
        $ident = str_repeat(' ', $this->getDepth() * 4);
        $res   = '';
        if ($pretty) {
            $res = $ident;
        }
        $res .= "<{$tag}";

        if ($this->props->isNotEmpty()) {
            $res .= " " . $this->props->map->render()->implode(' ');
        }
        $res .= ">";

        if ($this->hasValue()) {
            $res .= (string)$this->getValue();
        } elseif ($this->hasChildren()) {
            if ($pretty) {
                $res .= PHP_EOL;
            }
            $res .= $this->getChildren()->map->render($pretty)->implode('');
            if ($pretty) {
                $res .= $ident;
            }
        }
        $res .= "</{$tag}>";
        if ($pretty) {
            $res .= PHP_EOL;
        }
        if ($this->hasChildren()) {
        }
        return $res;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function getCollectionClass()
    {
        return $this->collectionClass;
    }

    public function setCollectionClass($collectionClass)
    {
        $this->collectionClass = $collectionClass;
        return $this;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    public function getEntry()
    {
        return $this->entry;
    }

    public function setEntry($entry)
    {
        $this->entry = $entry;
        return $this;
    }

    public function getAttrs()
    {
        return $this->attrs;
    }

    public function setAttrs($attrs)
    {
        $this->attrs = $attrs;
        return $this;
    }

    public function setAttr($attrs)
    {
        $this->attrs = $attrs;
        return $this;
    }

    public function attr($attrs)
    {
        $this->attrs = $attrs;
        return $this;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

}
