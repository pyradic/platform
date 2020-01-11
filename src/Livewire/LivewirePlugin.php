<?php

namespace Pyro\Platform\Livewire;

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;
use Illuminate\Support\Str;

class LivewirePlugin extends Plugin
{
    /** @noinspection ExplodeLimitUsageInspection */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('livewire', [ $this, 'livewire' ], [
                'needs_environment' => true,
                'needs_context'     => true,
            ]),
        ];
    }

    /**
     * @param \Anomaly\Streams\Platform\View\Twig\Bridge $context
     * @param array                                      $env = [ '__env' => new \Illuminate\View\Factory,  'app' => new \Illuminate\Foundation\Application,  'template' => new \Illuminate\View\Factory, '_instance' => new \stdClass ]
     * @param                                            $expression
     *
     * @return mixed|string
     */
    public function livewire($context, $env, $expression, ...$options)
    {
        $livewire = resolve('livewire');
        $lastArg  = trim(last(explode(',', $expression)));

        if (Str::startsWith($lastArg, 'key(') && Str::endsWith($lastArg, ')')) {
            $cachedKey = Str::replaceFirst('key(', '', Str::replaceLast(')', '', $lastArg));
            $args      = explode(',', $expression);
            array_pop($args);
            $expression = implode(',', $args);
        } else {
            $cachedKey = "'" . Str::random(7) . "'";
        }

        if ( ! isset($env[ '_instance' ])) {
            $dom = $livewire->mount($expression, ...$options)->dom;
        } elseif ($env[ '_instance' ]->childHasBeenRendered($cachedKey)) {
            $componentId  = $env[ '_instance' ]->getRenderedChildComponentId($cachedKey);
            $componentTag = $env[ '_instance' ]->getRenderedChildComponentTagName($cachedKey);
            $dom          = $livewire->dummyMount($componentId, $componentTag);
            $env[ '_instance' ]->preserveRenderedChild($cachedKey);
        } else {
            $response = $livewire->mount($expression, ...$options);
            $dom      = $response->dom;
            $env[ '_instance' ]->logRenderedChild($cachedKey, $response->id, $livewire->getRootElementTagName($dom));
        }
        return $dom;
    }

}
