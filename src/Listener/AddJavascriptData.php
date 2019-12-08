<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Asset\Asset;
use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Pyro\Platform\Event\PlatformWillRender;
use Pyro\Platform\Platform;

class AddJavascriptData
{
    /** @var \Illuminate\Contracts\Auth\Guard */
    private $guard;

    /** @var \Illuminate\Contracts\View\Factory */
    private $view;

    /** @var \Pyro\Platform\Platform */
    private $platform;

    public function __construct(Guard $guard, Factory $view)
    {
        $this->guard    = $guard;
        $this->view     = $view;
    }

//    public function handle(?TemplateDataIsLoading $event)
    public function handle(PlatformWillRender $event)
    {
        $this->platform = $event->getPlatform();
        $this->user();
//        $this->assets();
    }

    protected function user()
    {

        if ($this->guard->check()) {
            /** @var \Anomaly\UsersModule\User\UserModel $user */
            $this->view->share([ 'user' => $user = $this->guard->user() ]);
            $userData = collect($user->toArray())
                ->except([ 'activation_code', 'created_at', 'created_by_id', 'deleted_at', 'password', 'updated_at', 'updated_by_id' ])
                ->toArray();
            $this->platform->set('user', $userData);

            /** @var \Anomaly\UsersModule\User\UserPresenter|\Anomaly\UsersModule\User\Contract\UserInterface $decoratred */
            $decoratred =(new \Anomaly\Streams\Platform\Support\Decorator())->decorate($user);
            $this->platform->set('user.gravatar',$decoratred->gravatar()->url());

        }
    }

    protected function assets()
    {
        /** @var \Pyro\Platform\Asset\Asset $assets */
        $assets  = resolve(Asset::class);
        $assets = $assets->getCollections()->keys()->mapWithKeys(function($key) use ($assets){
            return [$key=> $assets->getInCollection($key)->values()->sortBy('index')];
        });
        $this->platform->global()->merge('assets',$assets->toArray());
    }
}
