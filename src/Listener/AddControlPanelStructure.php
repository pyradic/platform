<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Pyro\Platform\Ui\ControlPanel\Command\BuildControlPanelStructure;

class AddControlPanelStructure
{
    public function handle(TemplateDataIsLoading $event)
    {
        /** @var \Pyro\Platform\Ui\ControlPanel\ControlPanelStructure $structure */
        $structure = dispatch_now(new BuildControlPanelStructure());
        $template  = $event->getTemplate();
        $template->put('structure', $structure);
        platform()->set('cp.structure', $structure->translate('title'));
        platform()->set('cp.navigation', $structure->getActiveNavigation());
        platform()->set('cp.section', $structure->getActiveSection());
    }
}
