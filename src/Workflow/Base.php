<?php

namespace Pyro\Platform\Workflow;
use Illuminate\Contracts\Support\Arrayable;
use Laradic\Support\Traits\ArrayableProperties;
use Laradic\Support\Traits\ArrayAccessibleProperties;

class Base implements Arrayable, \ArrayAccess
{
    use ArrayAccessibleProperties;
    use ArrayableProperties;

    protected $unarrayable = ['unarrayable', 'workflow'];

    /** @var string */
    protected $slug;

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function __toString()
    {
        return $this->slug;
    }


}
