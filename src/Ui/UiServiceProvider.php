<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace Pyro\Platform\Ui;

use Illuminate\Support\ServiceProvider;

class UiServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [
        \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder::class => \Pyro\Platform\Ui\ControlPanel\ControlPanelBuilder::class,
        \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel::class        => \Pyro\Platform\Ui\ControlPanel\ControlPanel::class,
    ];

    public $singletons = [];

    public function boot()
    {

    }

    public function register()
    {

//        $this->app->events->listen(GatherNavigation::class, function (GatherNavigation $event) {
//            $event->getBuilder()->setNavigation(
//                collect($event->getBuilder()->getNavigation())->map([ Dot::class, 'wrap' ])->each(function (Dot $section) {
//                    $section->set('section', $section->get('section', NavigationLink::class));
//                })->toArray()
//            );
//        });
//        $this->app->events->listen(GatherSections::class, function (GatherSections $event) {
//            $event->getBuilder()->setSections(
//                collect($event->getBuilder()->getSections())->map([ Dot::class, 'wrap' ])->each(function (Dot $section) {
//                    $section->set('section', $section->get('section', Section::class));
//                })->toArray()
//            );
//        });
//        $this->app->events->listen(GatherShortcuts::class, function (GatherShortcuts $event) {
//            $event->getBuilder()->setShortcuts(
//                collect($event->getBuilder()->getShortcuts())->map([ Dot::class, 'wrap' ])->each(function (Dot $shortcut) {
//                    $shortcut->set('shortcut', $shortcut->get('shortcut', Shortcut::class));
//                })->toArray()
//            );
//        });
    }
}
