<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Arr;
use Pyro\Platform\Platform;

class AddUserToJavascript
{
    /** @var \Illuminate\Contracts\Auth\Guard */
    private $guard;

    /** @var \Illuminate\Contracts\View\Factory */
    private $view;

    /** @var \Pyro\Platform\Platform */
    private $platform;

    public function __construct(Guard $guard, Factory $view, Platform $platform)
    {
        $this->guard = $guard;
        $this->view = $view;
        $this->platform = $platform;
    }

    public function handle(TemplateDataIsLoading $event)
    {

        if ($this->guard->check()) {
            /** @var \Anomaly\UsersModule\User\UserModel $user */
            $this->view->share([ 'user' => $user = $this->guard->user() ]);
            $userData = collect($user->toArray())
                ->except([ 'activation_code', 'created_at', 'created_by_id', 'deleted_at', 'password', 'updated_at', 'updated_by_id' ])
                ->toArray();
            $this->platform->set('user', $userData);
        }
    }
}
