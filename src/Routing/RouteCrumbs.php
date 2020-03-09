<?php

namespace Pyro\Platform\Routing;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryPresenter;
use Closure;
use Evaluator;
use Hydrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Template;
use Translator;

class RouteCrumbs
{
    /** @var \Illuminate\Support\Collection */
    protected $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = collect();
    }

    /**
     * @param array                                 $route = \Pyro\IdeHelper\Examples\AddonServiceProviderExamples::routes()
     * @param \Anomaly\Streams\Platform\Addon\Addon $addon
     *
     * @return $this
     */
    public function addFromProvider($routeBreadcrumb, $route, $addon)
    {
        $breadcrumb = [];

        // normalize
        if ($routeBreadcrumb instanceof Closure) {

        } elseif (Arr::isAssoc($routeBreadcrumb)) {
            $breadcrumb = $routeBreadcrumb;
        } elseif (is_array($breadcrumb)) {
            $breadcrumb[ 'title' ]  = $routeBreadcrumb[ 0 ];
            $breadcrumb[ 'parent' ] = $routeBreadcrumb[ 1 ] ?? null;
        }

        foreach ([ 'parent', 'title', 'key' ] as $v) {
            if (isset($breadcrumb[ $v ])) {
                $needles = [ 'addon::', 'module::' ];
                if (Str::startsWith($breadcrumb[ $v ], $needles)) {
                    $breadcrumb[ $v ] = str_replace($needles, $addon->getNamespace() . '::', $breadcrumb[ $v ]);
                }
                if (Str::startsWith($breadcrumb[ $v ], '::')) {
                    $breadcrumb[ $v ] = Str::replaceFirst('::', $addon->getNamespace() . '::', $breadcrumb[ $v ]);
                }
            }
        }
        if ( ! Str::contains($breadcrumb[ 'parent' ], '::')) {
            $breadcrumb[ 'parent' ] = $addon->getNamespace($breadcrumb[ 'parent' ]);
        }
        if (isset($breadcrumb[ 'key' ]) && ! Str::contains($breadcrumb[ 'key' ], '::')) {
            $breadcrumb[ 'key' ] = $addon->getNamespace($breadcrumb[ 'key' ]);
        }

        $breadcrumb[ 'attributes' ] = data_get($breadcrumb, 'attributes', []);
        $breadcrumb[ 'class' ]      = data_get($breadcrumb, 'class');
        $breadcrumb[ 'key' ]        = data_get($breadcrumb, 'key', $route[ 'as' ]);
        $breadcrumb[ 'route' ]      = $route;
        $breadcrumb[ 'addon' ]      = $addon;
        $breadcrumb[ 'breadcrumb' ] = data_get($breadcrumb, 'breadcrumb', Breadcrumb::class);
        $breadcrumb[ 'url' ]        = data_get($breadcrumb, 'url');
        $breadcrumb[ 'entry' ]      = data_get($route, 'entry');
        $breadcrumb[ 'truncate' ]      = data_get($breadcrumb, 'truncate', 20);

        $this->breadcrumbs->push($breadcrumb);
        return $this;
    }

    public function findBreadcrumb($key)
    {
        $breadcrumb = $this->breadcrumbs->where('key', $key)->first();
        if ($breadcrumb !== null) {
            $breadcrumb = collect($breadcrumb);
        }
        return $breadcrumb;
    }

    public function entry(Request $request = null) //, $entry = null)
    {
        $route = $request ? $request->route() : \Request::route();
        if ( ! $route) {
            return [];
        }
        $bc = $this->findBreadcrumb($route->getName());
        if ( ! $bc) {
            return [];
        }

        // collect parents
        $bcs    = collect();
        $routes = resolve(Router::class)->getRoutes();
        while ($bc) {
            $bcs[]             = $bc;
            $bc[ 'variables' ] = [];
            $bcRoute = $routes->getByName($bc[ 'key' ]) ?? $routes->getByName($bc['route']['as']);
            if ($bcRoute) {
                $bc[ 'route' ] = $bcRoute;
                $compiled      = $bcRoute->getCompiled();
                if ( ! $compiled) {
                    $compiled = ($rc = new \Illuminate\Routing\RouteCompiler($bcRoute))->compile();
                }
                $bc[ 'variables' ] = $compiled->getPathVariables();
            }
            $bc = $this->findBreadcrumb($bc[ 'parent' ]);
        }

        $entries = $route->parameters();
        foreach ($bcs as $bc) {
//            $entries = array_replace($entries, $route->parameters());
            foreach ($bc[ 'variables' ] as $variable) {
                if ( ! array_key_exists($variable, $entries)) {
                    foreach ($entries as $name => $_entry) {
                        if (
                            ($_entry instanceof EntryInterface || $_entry instanceof EntryPresenter)
                            && $_entry->assignmentIsRelationship($variable)
                        ) {
                            $_entry->loadMissing($variable);
                            $entries[ $variable ] = $_entry->getAttribute($variable);
                        }
                    }
                }
            }
        }
        foreach ($entries as &$entry) {
            if ($entry instanceof EntryPresenter === false) {
                $entry = $entry->getPresenter();
            }
        }
        $breadcrumbs = collect();
        foreach ($bcs as $bc) {
            if ( ! isset($bc[ 'url' ]) || $bc[ 'url' ] === null) {
                if ($bc[ 'route' ] instanceof Route) {
                    $bc[ 'url' ] = route($bc[ 'route' ]->getName(), $route->parameters());
                } else {
//                    if($route=$routes->getByName($bc['route']['as'])){
//                        $bc[ 'url' ] =
//                    }
                }
            }
            $bc = $bc->toArray();
            $bc = Translator::translate($bc);
            if ( ! empty($entries)) {
                $bc = Evaluator::evaluate($bc, $entries);
                $bc = $this->render($bc, $entries);
//                $bc = Parser::parse($bc, $entries);
            }
            $bc = Translator::translate($bc);
            if(is_int($bc['truncate'])){
                $bc['title'] = Str::truncate($bc['title'], $bc['truncate'], '..');
            }
            Hydrator::hydrate($breadcrumb = app()->build($bc[ 'breadcrumb' ]), Collection::unwrap($bc));
            $breadcrumbs->push($breadcrumb);
        }
        platform()->set('breadcrumbs', array_reverse($breadcrumbs->except('route')->toArray()));
        return $breadcrumbs;
    }

    protected function render($target, $data)
    {
        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = $this->render($item, $data);
            }
        } elseif (is_string($target) && str_contains($target, [ '{{', '{%' ])) {
            $target = (string)Template::render($target, $data);
        }
        return $target;
    }
}
