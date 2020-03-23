<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;

class RegisterAddonWorkflows
{
    public function handle(AddonsHaveRegistered $event)
    {
        /** @var \Laradic\Workflow\WorkflowRegistry $workflowRegistry */
        $workflowRegistry = resolve('workflow');
        foreach($event->getAddons()->withConfig('workflows')->all() as $addon){
            $workflows = config($addon->getNamespace('workflows'), []);
            foreach($workflows as $key => $workflowConfig){
//                $name = $addon->getNamespace($key);
                $name = $addon->getSlug() . '::' . $key;
                $workflowRegistry->addFromArray($name, $workflowConfig);
            }
        }
    }
}
