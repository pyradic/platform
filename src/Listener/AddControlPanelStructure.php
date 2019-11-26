<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Ui\ControlPanel\Event\ControlPanelWasBuilt;
use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Pyro\Platform\Ui\ControlPanel\Command\BuildControlPanelStructure;

class AddControlPanelStructure
{
    public function handle2(ControlPanelWasBuilt $event)
    {
        $structure = dispatch_now(new BuildControlPanelStructure(false));
        $cp = $event->getBuilder()->getControlPanel();
        return;
    }
    public function handle(TemplateDataIsLoading $event)
    {
        $structure = dispatch_now(new BuildControlPanelStructure());
        $event->getTemplate()->put('structure', $structure);
    }
}
