<?php

namespace Pyro\Platform\Workflow;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;

class RegisterAddonWorkflows
{
    public function handle(AddonsHaveRegistered $event)
    {
        $workflowManager = resolve(WorkflowManager::class);
        foreach ($event->getAddons()->withConfig('workflows')->all() as $addon) {
            $workflows = config($addon->getNamespace('workflows'), []);
            foreach ($workflows as $key => $data) {
                $data[ 'addon' ] = $addon;
                $workflowManager->addFromArray(
                    $addon->getNamespace('workflow.' . $key),
                    $data
                );
            }
        }
    }
}
