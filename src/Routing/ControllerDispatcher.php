<?php

namespace Pyro\Platform\Routing;

use Illuminate\Routing\Route;

class ControllerDispatcher extends \Illuminate\Routing\ControllerDispatcher
{
    public function dispatch(Route $route, $controller, $method)
    {
        $hasBreadcrumb = isset($route->action['breadcrumb']);

        $parameters = $this->resolveClassMethodDependencies(
            $route->parametersWithoutNulls(), $controller, $method
        );
        if($hasBreadcrumb){
            $this->container->make('route_crumbs')->entry(request());
        }
        if (method_exists($controller, 'callAction')) {
            return $controller->callAction($method, $parameters);
        }

        return $controller->{$method}(...array_values($parameters));
    }

}
