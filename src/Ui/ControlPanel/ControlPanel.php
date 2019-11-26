<?php

namespace Pyro\Platform\Ui\ControlPanel;

use Illuminate\Contracts\Support\Arrayable;

class ControlPanel extends \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel implements Arrayable
{
    public function setButtons($buttons)
    {
        $this->buttons = $buttons;
        return $this;
    }

    public function setSections($sections)
    {
        $this->sections = $sections;
        return $this;
    }

    public function setShortcuts($shortcuts)
    {
        $this->shortcuts = $shortcuts;
        return $this;
    }

    public function setNavigation($navigation)
    {
        $this->navigation = $navigation;
        return $this;
    }

    public function toArray()
    {
        return [
            'button' => $this->buttons->toArray(),
            'sections' => $this->sections->toArray(),
            'shortcuts' => $this->shortcuts->toArray(),
            'navigation' => $this->navigation->toArray(),
        ];
    }
}
