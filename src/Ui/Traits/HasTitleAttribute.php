<?php

namespace Pyro\Platform\Ui\Traits;

trait HasTitleAttribute
{
    /** @var string */
    protected $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }


}
