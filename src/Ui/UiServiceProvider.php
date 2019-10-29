<?php

namespace Pyro\Platform\Ui;

use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\Event\GatherShortcuts;
use Anomaly\Streams\Platform\Ui\ControlPanel\Event\ControlPanelIsBuilding;
use Anomaly\Streams\Platform\Ui\ControlPanel\Event\ControlPanelWasBuilt;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Pyro\Platform\Ui\ControlPanel\Section;
use Pyro\Platform\Ui\ControlPanel\Shortcut;

class UiServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [];

    public $singletons = [];

    public function boot()
    {

    }

    protected $controlPanel = [
        'shortcuts' => [ 'shortcut' => Shortcut::class, ],
        'sections'  => [ 'section' => Section::class, ],
    ];

    public function register()
    {
        $this->app->events->listen(GatherShortcuts::class, function (GatherShortcuts $event) {
            $builder   = $event->getBuilder();
            $shortcuts = $builder->getShortcuts();
            $shortcuts = array_map(function ($shortcut) {
                foreach ($this->controlPanel[ 'shortcuts' ] as $key => $value) {
                    if ( ! Arr::has($shortcut, $key)) {
                        Arr::set($shortcut, $key, $value);
                    }
                }
                return $shortcut;
            }, $shortcuts);
            $builder->setShortcuts($shortcuts);
        });
    }
}
