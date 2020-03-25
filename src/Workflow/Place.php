<?php

namespace Pyro\Platform\Workflow;


class Place
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    public $workflow;

    /** @var string */
    public $slug;

    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }
}
