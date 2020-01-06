<?php

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;
use Illuminate\Support\Str;

class PlatformPlugin extends Plugin
{
    /** @noinspection ExplodeLimitUsageInspection */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('livewire', function ($expression) {
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

                $dom = $livewire->mount($expression)->dom;
//                if ( ! isset($_instance)) {
//                } elseif ($_instance->childHasBeenRendered($cachedKey)) {
//                    $componentId  = $_instance->getRenderedChildComponentId($cachedKey);
//                    $componentTag = $_instance->getRenderedChildComponentTagName($cachedKey);
//                    $dom          = $livewire->dummyMount($componentId, $componentTag);
//                    $_instance->preserveRenderedChild($cachedKey);
//                } else {
//                    $response = $livewire->mount($expression);
//                    $dom      = $response->dom;
//                    $_instance->logRenderedChild($cachedKey, $response->id, $livewire->getRootElementTagName($dom));
//                }
                return $dom;
            }),
        ];
    }

}
