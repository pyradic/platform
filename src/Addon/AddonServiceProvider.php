<?php

namespace Pyradic\Platform\Addon;

class AddonServiceProvider extends \Anomaly\Streams\Platform\Addon\AddonServiceProvider
{

    /**
     * @var array = [
     *     $url => [
     *          'as' => '',
     *          'uses' => '',
     *          'verb' => '',
     *          'ttl' => 0,
     *          'csrf' => true,
     *          'middleware' => [],
     *          'where' => [],
     *          'streams::httpcache'  => false,
     *          'streams::addon' => '',
     *
     *          'anomaly.module.users::permission' => 'vendor.module.example::widgets.test',
     *          'anomaly.module.users::permission' => ['vendor.module.example::widgets.test'],
     *
     *          'anomaly.module.users::role' => 'vendor.module.example::widgets.test',
     *          'anomaly.module.users::role' => ['vendor.module.example::widgets.test'],
     *
     *          'anomaly.module.users::redirect' => '/',
     *          'anomaly.module.users::route' => 'vendor.module.example::route.name',
     *          'anomaly.module.users::intended' => ''
     *          'anomaly.module.users::message' => 'Sorry, you do not have access.',
     *      ]
     * ]
     */
    protected $routes = [];

}
