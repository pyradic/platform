<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\Theme\ThemeCollection;
use Anomaly\Streams\Platform\Event\Ready;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pyro\Platform\Command\AddAddonOverrides;
use Pyro\Platform\Command\AddPathOverrides;

class OverrideAddons
{
    use DispatchesJobs;

    /** @var \Anomaly\Streams\Platform\Addon\Theme\ThemeCollection */
    private $themes;

    /** @var \Anomaly\Streams\Platform\Addon\AddonCollection */
    private $addons;

    public function __construct(ThemeCollection $themes, AddonCollection $addons)
    {

        $this->themes = $themes;
        $this->addons = $addons;
    }
    public function handle(Ready $event)
    {
        $this->dispatchNow(new AddPathOverrides(path_join(__DIR__, '..', 'resources')));

        $active = $this->themes->active();
        $this->dispatchNow(new AddAddonOverrides($active));

        $installed = $this->addons->installed()->enabled();
        foreach($installed as $addon){
            $this->dispatchNow(new AddAddonOverrides($addon));
        }
    }
}
