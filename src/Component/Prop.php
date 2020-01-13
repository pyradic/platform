<?php

namespace Pyro\Platform\Component;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Laradic\Support\Traits\ArrayableProperties;
use Laradic\Support\Traits\ArrayAccessibleProperties;
use Stringy\Stringy;

class Prop implements ArrayAccess, Arrayable, JsonSerializable
{
    use Macroable;
    use ArrayableProperties;
    use ArrayAccessibleProperties;

    /** @var \Pyro\Platform\Component\ComponentInterface */
    protected $component;

    /** @var string */
    protected $binding = 'v-bind'; // 'v-bind' | 'v-on' | 'v-if'

    /** @var string */
    protected $value;

    /** @var string */
    protected $name; // $name . ':' . $arg . '="' .  $value . '"';

    /** @var \Illuminate\Support\Collection|string[]|array<string> */
    protected $modifiers = [];

    /**
     * Prop constructor.
     *
     * @param \Pyro\Platform\Component\ComponentInterface $component
     */
    public function __construct($component = null)
    {
        $this->component = $component;
    }

    public function render()
    {
        $res = '';

        $render = Stringy::create();
        $render->append($this->binding);

        if ($this->binding) {
            $res .= $this->binding;
            if ( ! Str::endsWith($res, ':')) {
                $res .= ':';
            }
        }

        if ($this->name) {
            $res .= $this->name;
        }
        if (count($this->modifiers) > 1) {
            $res .= '.' . implode('.', $this->modifiers);
        }

        if ($this->value) {
            $res .= "=\"{$this->processValue()}\"";
        }

        return $res;
    }

    public function processValue()
    {
        return $this->value;
    }

    public function ensureModifier($name)
    {
        if ( ! $this->hasModifier($name)) {
            $this->modifiers[] = $name;
        }
        return $this;
    }

    public function hasModifier($name)
    {
        return $this->modifiers->contains($name);
    }

    // region: implements

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    // endregion

    // region: getters & setters

    public function getBinding()
    {
        return $this->binding;
    }

    public function setBinding($binding)
    {
        $this->binding = $binding;
        return $this;
    }

    public function hasValue()
    {
        return $this->value !== null;
    }

    public function getValue($default = null)
    {
        return $this->hasValue() ? $this->value : $default;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function hasArg()
    {
        return $this->name !== null;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getModifiers()
    {
        return $this->modifiers;
    }

    public function setModifiers($modifiers)
    {
        $this->modifiers = $modifiers;
        return $this;
    }

    // endregion

}
