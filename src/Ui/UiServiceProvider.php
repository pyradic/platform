<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace Pyro\Platform\Ui;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;

class UiServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [
//        \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder::class => \Pyro\Platform\Ui\ControlPanel\ControlPanelBuilder::class,
        \Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel::class                        => \Pyro\Platform\Ui\ControlPanel\ControlPanel::class,
        \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationLink::class => \Pyro\Platform\Ui\ControlPanel\Component\NavigationLink::class,
        \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Section::class           => \Pyro\Platform\Ui\ControlPanel\Component\Section::class,
        \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\Shortcut::class         => \Pyro\Platform\Ui\ControlPanel\Component\Shortcut::class,
        \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionInput::class      => \Pyro\Platform\Ui\ControlPanel\Component\SectionInput::class,

        \Anomaly\Streams\Platform\Ui\Table\Component\Column\ColumnNormalizer::class => \Pyro\Platform\Ui\Table\ColumnNormalizer::class,
        \Anomaly\Streams\Platform\Ui\Table\Component\Column\ColumnBuilder::class    => \Pyro\Platform\Ui\Table\ColumnBuilder::class,
    ];

    public $singletons = [];

    public function boot(Router $router)
    {
//        Input::elp()
//            ->share([
//                'router'  => $router,
//                'app'     => app(),
//                'request' => request(),
//                'auth'    => auth(),
//            ]);
    }

    public function register()
    {
        $this->registerFormBuilderEntrySetsMode();
        $this->registerLoginFormBuilderAutoFill();
    }

    protected function registerLoginFormBuilderAutoFill()
    {
        /*
         * Development Feature - Auto-fill login for using the admin's email/password.
         * Not enabled on production. Requires app.debug to be true
         */
        if ($this->app->environment('local') && $this->app->config[ 'app.debug' ]) {
            $this->app->extend('login', function (LoginFormBuilder $login) {
                $login->on('built', function (LoginFormBuilder $builder) {
                    $email    = env('ADMIN_EMAIL');
                    $password = env("ADMIN_PASSWORD");
                    if (resolve(Agent::class)->is('Firefox')) {
                        if (resolve(UserRepositoryInterface::class)->findByEmail('admin2@test.com')) {
                            $email    = 'admin2@test.com';
                            $password = 'test';
                        }
                    }
                    $builder->getFormField('email')->setValue($email);
                    $builder->getFormField('password')->setValue($password);
                });
                return $login;
            });
        }
    }
    protected function registerFormBuilderEntrySetsMode()
    {
        FormBuilder::when('entry_set', function (FormBuilder $builder) {
            if ( ! $builder->getFormMode()) {
                $builder->setFormMode(
                    ($builder->getFormEntryId() || $builder->getEntry()) ? 'edit' : 'create'
                );
            }
        });
    }
}
