<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyro\Platform\Fixes;

use Anomaly\Streams\Platform\Ui\ControlPanel\Event\ControlPanelWasBuilt;
use Anomaly\Streams\Platform\Ui\Table\Component\Action\Command\BuildActions;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Anomaly\UsersModule\Role\Table\RoleTableBuilder;
use Anomaly\UsersModule\User\Table\UserTableBuilder;
use Illuminate\Support\ServiceProvider;
use Pyro\Platform\Bus\Dispatcher;

class FixesServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [];

    public $singletons = [];

    /** @var \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel */
    protected $cp;

    public function boot()
    {

    }

    public function register()
    {
//        Dispatcher::before(\Anomaly\Streams\Platform\Ui\Table\Component\Action\Command\BuildActions::class, function (BuildActions $command) {
//            $a       = $command;
//            $builder = $this->app->make(ActionBuilder::class);
//            $command->handle($builder);
//            return false;
//        });
//
//        $this->app->events->listen(ControlPanelWasBuilt::class, function(ControlPanelWasBuilt $e){
//            $this->cp=$e->getBuilder()->getControlPanel();
//        });
//        $this->app->extend(UserTableBuilder::class, function($t){
//            return $t;
//        });
    }
}
