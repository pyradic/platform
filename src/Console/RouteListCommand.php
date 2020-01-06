<?php

namespace Pyro\Platform\Console;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;

class RouteListCommand extends \Illuminate\Foundation\Console\RouteListCommand
{
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = [ 'hide', null, InputOption::VALUE_OPTIONAL, 'The column(s) to hide (host, method, uri, name, action, middleware)' ];

        return $options;
    }

    public function setInput($params=[], $defs=null)
    {
        $this->input = new ArrayInput($params, $defs);
        return $this;
    }
    public function setInputFromArguments($arguments)
    {
        $this->input = $this->createInputFromArguments($arguments);
        return $this;
    }

    public function routes()
    {
        return $this->getRoutes();
    }

    protected function displayRoutes(array $routes)
    {
        if (is_string($this->option('hide'))) {
            $routes = array_map([ $this, 'hideColumns' ], $routes);

            $columns = explode(',', $this->option('hide'));
            $hide    = collect($this->headers)
                ->mapWithKeys(function ($name, $key) {
                    $slug = Str::slug(strtolower($name));
                    return [ $key => compact('name', 'key', 'slug') ];
                })
                ->filter(function ($header) use ($columns) {
                    return in_array($header[ 'slug' ], $columns);
                })
                ->each(function ($header) {
                    unset($this->headers[ $header[ 'key' ] ]);
                });
        }
        parent::displayRoutes($routes);
    }

    public function hideColumns($route)
    {
        $columns = explode(',', $this->option('hide'));

        return Arr::except($route, $columns);
    }

    /**
     * Get before filters.
     *
     * @param \Illuminate\Routing\Route $route
     *
     * @return string
     */
    protected function getMiddleware($route)
    {
        try {
            return collect($route->gatherMiddleware())->map(function ($middleware) {
                return $middleware instanceof Closure ? 'Closure' : $middleware;
            })->implode(',');
        }
        catch (\ReflectionException $e) {
            return '';
        }
    }
}
