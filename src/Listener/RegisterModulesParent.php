<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Command\BuildSections;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Guesser\HrefGuesser;
use Pyro\Platform\Addon\Module\Module;

class RegisterModulesParent
{

    public function handle(AddonsHaveRegistered $event)
    {
        $addons = $event->getAddons();
        foreach ($addons as $module) {
            if ( ! $module instanceof Module || ! $module->hasParent() || ! $addons->has($module->getParent())) {
                continue;
            }
            /** @var \Anomaly\Streams\Platform\Addon\Module\Module $parent */
            $parent = $addons->get($module->getParent());
            $module->setNavigation(false);
            $sections = $module->getSections();

            if (count($sections) === 1) {
                $slug = array_keys($sections)[ 0 ];
                return $parent->addSection($slug, $sections[ $slug ]);
            }
            $parent->addSection($module->getSlug(), [
                'title'    => $module->getTitle(),
                'sections' => $sections,
            ]);
        }
    }
}
