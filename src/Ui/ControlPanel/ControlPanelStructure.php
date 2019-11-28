<?php

namespace Pyro\Platform\Ui\ControlPanel;

use Illuminate\Support\Collection;

class ControlPanelStructure extends Collection
{
    public function getActiveNavigation()
    {
        return $this->firstWhere('active', true);
    }

    public function getActiveSection()
    {
        if($navigation = $this->getActiveNavigation()) {
            return $navigation->get('children')->firstWhere('active', true);
        }
    }
}
