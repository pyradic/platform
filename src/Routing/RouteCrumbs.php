<?php

namespace Pyro\Platform\Routing;

use Closure;
use Evaluator;
use Hydrator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Parser;
use Translator;
use Value;

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

        foreach ([ 'parent', 'title' ] as $v) {
            if ($breadcrumb[ $v ]) {
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

        $breadcrumb[ 'attributes' ] = data_get($breadcrumb, 'attributes', []);
        $breadcrumb[ 'class' ]      = data_get($breadcrumb, 'class');
        $breadcrumb[ 'key' ]        = data_get($breadcrumb, 'key', $route[ 'as' ]);
        $breadcrumb[ 'route' ]      = $route;
        $breadcrumb[ 'addon' ]      = $addon;
        $breadcrumb[ 'breadcrumb' ] = data_get($breadcrumb, 'breadcrumb', Breadcrumb::class);
        $breadcrumb[ 'url' ]        = data_get($breadcrumb, 'url');
        $breadcrumb[ 'entry' ]      = data_get($route, 'entry');

        $this->breadcrumbs->push($breadcrumb);
        return $this;
    }

    public function entry(Request $request = null, $entry = null)
    {
        $route = $request ? $request->route() : \Request::route();
        if ( ! $route) {
            return [];
        }
        $bc = $this->breadcrumbs->where('key', $route->getName())->first();
        if ( ! $bc) {
            return [];
        }

        // collect parents
        $bcs  = collect();
        $loop = true;
        while ($loop) {
            $bcs[] = $bc;
            $bc    = $this->breadcrumbs->where('key', $bc[ 'parent' ])->first();
            $loop  = $bc !== null;
        }

        if ($entry === null) {
            $entryBc = $bcs->firstWhere('entry', '!=', null);
            $entry   = $route->parameter($entryBc[ 'entry' ]);
        }
        if ($entry === null) {
            $entry = head($route->parameters());
        }
        $breadcrumbs = collect();

        foreach ($bcs as $bc) {
            if ( ! isset($bc[ 'url' ])) {
                $bc[ 'url' ] = route($bc[ 'route' ][ 'as' ], $route->parameters());
            }
            $bc = Translator::translate($bc);
            if ($entry) {
                $bc            = Evaluator::evaluate($bc, compact('entry'));
                $bc            = Parser::parse($bc, compact('entry'));
                $bc[ 'title' ] = Value::make($bc[ 'title' ], $entry);
                $bc[ 'url' ]   = $bc[ 'url' ] ? Value::make($bc[ 'url' ], $entry) : null;
            }
            Hydrator::hydrate($breadcrumb = app()->build($bc[ 'breadcrumb' ]), $bc);
            $breadcrumbs->push($breadcrumb);
        }
        platform()->set('breadcrumbs', array_reverse($breadcrumbs->toArray()));
        return $breadcrumbs;
    }
}
