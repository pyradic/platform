<?php

namespace Pyradic\Platform\Console;

use Illuminate\Console\Command;
use Barryvdh\Reflection\DocBlock;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

class IdeHelperPlatformCommand extends Command
{
    protected $signature = 'ide-helper:platform';

    public function handle()
    {
        //= [$i=>['as' => '', 'uses' => '', 'prefix' => '', 'middleware' => [], 'domain' => '','controller' =>'']]
        $this->ensureAddonServiceProvider();
    }

    protected function ensureAddonServiceProvider()
    {
        $content    = file_get_contents($path = base_path('vendor/anomaly/streams-platform/src/Addon/AddonServiceProvider.php'));
        $cls        = new \ReflectionClass(AddonServiceProvider::class);
        $method     = $cls->getMethod('getRoutes');
        $docComment = $method->getDocComment();
        $docblock   = new DocBlock($docComment, new DocBlock\Context($method->getNamespaceName()));
        if($docblock->hasTag('return')){
            /** @var \Barryvdh\Reflection\DocBlock\Tag\ReturnTag $tag */
            $tag = head($docblock->getTagsByName('return'));
            if(trim($tag->getContent()) === 'array'){

            }
            $content=$tag->getContent();
            $type=$tag->getType();
            $types=$tag->getTypes();
        }

        return;
    }
}