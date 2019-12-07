<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Pyro\Platform\Command\GetClassArray;
use Pyro\Platform\Ui\ControlPanel\Command\BuildControlPanelStructure;

class AddControlPanelStructure
{
    public function handle(TemplateDataIsLoading $event)
    {
        /** @var \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel $cp */
        $cp = $event->getTemplate()->get('cp');

        /** @var \Pyro\Platform\Ui\ControlPanel\ControlPanelStructure $structure */
        $structure = dispatch_now(new BuildControlPanelStructure());
        $template  = $event->getTemplate();
        $template->put('structure', $structure);
        platform()->set('cp.structure', $structure->translate('title'));

        $navigation = $section = null;

        if ($cp) {
            if ($active = $cp->getNavigation()->active()) {
                $navigation = $structure->firstWhere('slug', $active->getSlug());
                if ($active = $cp->getActiveSection()) {
                    /** @var \Illuminate\Support\Collection $section */
                    $section = $navigation->get('children')->firstWhere('slug', $active->getSlug());
                    $section->put('active', true);
                }
            }

            $shortcuts = $cp->getShortcuts()->map(function ($shortcut) {
                return dispatch_now(new GetClassArray($shortcut));
            });

            platform()->set('cp.navigation', $navigation);
            platform()->set('cp.section', $section);
            platform()->set('cp.shortcuts', $shortcuts);

        }
    }
}
