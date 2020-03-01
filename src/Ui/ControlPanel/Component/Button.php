<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

class Button extends \Anomaly\Streams\Platform\Ui\Button\Button
{


    protected $key;

    protected $slug;

    protected $sectionKey;

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSectionKey()
    {
        return $this->sectionKey;
    }

    public function setSectionKey($sectionKey)
    {
        $this->sectionKey = $sectionKey;
        return $this;
    }


    public function getUrl()
    {
        return $this->url ?? $this->getAttributes()['href'];
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getText();
    }

    /**
     * Set the title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->setText($title);
    }
}
