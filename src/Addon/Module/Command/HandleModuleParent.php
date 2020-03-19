<?php

namespace Pyro\Platform\Addon\Module\Command;

use Pyro\Platform\Addon\Module\Module;

class HandleModuleParent
{
    /** @var \Anomaly\Streams\Platform\Addon\Module\Module */
    protected $module;

    public function handle()
    {
        $module = $this->module;
        if ( ! $module instanceof Module) {
            return;
        }
        if ( ! $module->hasParent()) {
            return;
        }

    }

}
