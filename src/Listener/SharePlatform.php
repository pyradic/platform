<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Illuminate\Contracts\View\Factory;
use Pyro\Platform\Platform;

class SharePlatform
{
    public function handle(TemplateDataIsLoading $event)
    {
        $platform = resolve(Platform::class);
        $view     = resolve(Factory::class);

        $view->share('platform', $platform);
        $event->getTemplate()->set('platform', $platform);
    }
}
