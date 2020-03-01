<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Pyro\Platform\Command\GetClassArray;
use Pyro\Platform\Ui\ControlPanel\Command\TransformControlPanelNavigation;
use Pyro\Platform\Ui\ControlPanel\Component\NavigationNode;
use Pyro\Platform\Ui\Input;

class AddControlPanelToJS
{
    public function handle(TemplateDataIsLoading $event)
    {
        /** @var \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel $cp */
        $cp = $event->getTemplate()->get('cp');

        /** @var \Pyro\Platform\Ui\ControlPanel\Component\NavigationNode $node */
        $node = dispatch_now(new TransformControlPanelNavigation());
        foreach ($node->getChildren() as $child) {
            $this->disableSubmenuIcons($child);
            if ($child->hasChildren()) {
                foreach ($child->getChildren() as $child2) {
                    $this->disableSubmenuIcons($child2);
                }
            }
        }

        $navigation = $node->toArray();
        $navigation = Input::translate($navigation);
        platform()->set('cp.navigation', $navigation);

        if ($cp) {
            $shortcuts = $cp->getShortcuts()->map(function ($shortcut) {
                $shortcut = dispatch_now(new GetClassArray($shortcut));
                $shortcut = Input::translate($shortcut);
                return $shortcut;
            })->toArray();

            $buttons = Input::translate($cp->getButtons()->toArray());

            platform()->set('cp.navigation', $navigation);
            platform()->set('cp.shortcuts', $shortcuts);
            platform()->set('cp.buttons', $buttons);
        }
    }

    protected function disableSubmenuIcons(NavigationNode $node)
    {
        $value                             = $node->getValue();
        $attributes                        = $value->getAttributes();
        $attributes[ ':no-submenu-icons' ] = true;
        $value->setAttributes($attributes);
    }
}
