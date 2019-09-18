<?php

namespace Pyradic\Platform\Console;

use ReflectionClass;
use ReflectionException;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Barryvdh\Reflection\DocBlock;
use Barryvdh\Reflection\DocBlock\Tag;
use Barryvdh\Reflection\DocBlock\Context;
use Symfony\Component\Console\Input\InputOption;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Barryvdh\Reflection\DocBlock\Serializer as DocBlockSerializer;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;

class IdeHelperStreamsCommand extends Command
{
    protected $name = 'ide-helper:streams';

    protected $description = 'Generate autocompletion for stream classes';

    public function handle(StreamRepositoryInterface $repo, AddonCollection $addons)
    {
        $ignoreCoreAddons   = $this->option('core') !== true;
        $ignoreSharedAddons = $this->option('shared') !== true;

        foreach ($addons as $addon) {
            /** @var \Anomaly\Streams\Platform\Addon\Module\Module $addon */
            if ($ignoreCoreAddons && $addon->isCore()) {
                continue;
            }
            if ($ignoreSharedAddons && $addon->isShared()) {
                continue;
            }
            if ($addon->getType() === 'module') {
                $streams = $repo->findAllByNamespace('users');
//                $this->line("Got [{$streams->count()}] streams for addon [{$addon->getNamespace()}]");
//continue;
                foreach ($streams as $stream) {
                    /** @var \Anomaly\Streams\Platform\Stream\Contract\StreamInterface $stream */

                    $s         = $stream->toArray();
                    $name      = ucfirst(camel_case(str_singular($stream->getSlug())));
                    $path      = $addon->getPath('src/' . $name);
                    $namespace = with(new ReflectionClass($addon))->getNamespaceName();
                    if (is_dir($path)) {
                        $this->comment("Generating PHPDoc for stream [{$stream->getNamespace()}.{$stream->getSlug()}]");
                        $this->generateStreamPhpDocs($path, $namespace);
                    }
                }
            }
        }
    }

    protected function getOptions()
    {
        return [
            [ 'core', null, InputOption::VALUE_NONE, 'Include core addons?' ],
            [ 'shared', null, InputOption::VALUE_NONE, 'Include shared addons?' ],
        ];
    }

    protected function generateStreamPhpDocs($path, $namespace)
    {
        $name                     = pathinfo($path, PATHINFO_BASENAME);
        $modelClass               = "\\{$namespace}\\{$name}\\{$name}Model";
        $collectionClass          = "\\{$namespace}\\{$name}\\{$name}Collection";
        $criteriaClass            = "\\{$namespace}\\{$name}\\{$name}Criteria";
        $observerClass            = "\\{$namespace}\\{$name}\\{$name}Observer";
        $presenterClass           = "\\{$namespace}\\{$name}\\{$name}Presenter";
        $repositoryClass          = "\\{$namespace}\\{$name}\\{$name}Repository";
        $routerClass              = "\\{$namespace}\\{$name}\\{$name}Router";
        $seederClass              = "\\{$namespace}\\{$name}\\{$name}Seeder";
        $interfaceClass           = "\\{$namespace}\\{$name}\\Contract\\{$name}Interface";
        $repositoryInterfaceClass = "\\{$namespace}\\{$name}\\Contract\\{$name}RepositoryInterface";

        $this->ensureTag($interfaceClass, 'mixin', $modelClass);
        $this->ensureTag($repositoryInterfaceClass, 'mixin', $repositoryClass);
        $this->ensureTag($presenterClass, 'mixin', $modelClass);

        $this->ensureTag($repositoryClass, 'method', "{$interfaceClass} first(\$direction = 'asc')");
        $this->ensureTag($repositoryClass, 'method', "{$interfaceClass} find(\$id)");
        $this->ensureTag($repositoryClass, 'method', "{$interfaceClass} findBy(\$key, \$value)");
        $this->ensureTag($repositoryClass, 'method', "{$collectionClass}|{$interfaceClass}[] findAllBy(\$key, \$value)");
        $this->ensureTag($repositoryClass, 'method', "{$collectionClass}|{$interfaceClass}[] findAll(array \$ids)");
        $this->ensureTag($repositoryClass, 'method', "{$interfaceClass} create(array \$attributes)");
        $this->ensureTag($repositoryClass, 'method', "{$interfaceClass} getModel()");
        $this->ensureTag($repositoryClass, 'method', "{$interfaceClass} newInstance(array \$attributes = [])");

        $this->ensureTag($collectionClass, 'method', "{$interfaceClass}[] all()");
        $this->ensureTag($collectionClass, 'method', "{$interfaceClass} find(\$key, \$default=null)");
        $this->ensureTag($collectionClass, 'method', "{$interfaceClass} findBy(\$key, \$value)");

        $this->writeAndFlushCached();
    }
//
//    protected function generateStreamDocs($path, $namespace)
//    {
//        $name = pathinfo($path, PATHINFO_BASENAME);
//        $c    = collect([
//            'model'               => "\\{$namespace}\\{$name}\\{$name}Model",
//            'collection'          => "\\{$namespace}\\{$name}\\{$name}Collection",
//            'criteria'            => "\\{$namespace}\\{$name}\\{$name}Criteria",
//            'observer'            => "\\{$namespace}\\{$name}\\{$name}Observer",
//            'presenter'           => "\\{$namespace}\\{$name}\\{$name}Presenter",
//            'repository'          => "\\{$namespace}\\{$name}\\{$name}Repository",
//            'router'              => "\\{$namespace}\\{$name}\\{$name}Router",
//            'seeder'              => "\\{$namespace}\\{$name}\\{$name}Seeder",
//            'interface'           => "\\{$namespace}\\{$name}\\Contract\\{$name}Interface",
//            'repositoryInterface' => "\\{$namespace}\\{$name}\\Contract\\{$name}RepositoryInterface",
//        ])->cast(ClassDoc::class);
//
//
//
//        $repo = new ClassDoc('$repositoryClass');
//
//        $repo->process();
//    }

    protected $cache = [];

    protected function writeAndFlushCached()
    {
        foreach ($this->cache as $class => $data) {
            $reflection = $data[ 'reflection' ];
            $phpdoc     = $data[ 'phpdoc' ];
            $this->writeDocBlock($reflection, $phpdoc, $reflection->getDocComment());
        }
        $this->cache = [];
    }

    protected function ensureTag(string $class, string $tagName, string $tagContent)
    {
        try {
            if ( ! array_key_exists($class, $this->cache)) {
                $reflection            = new ReflectionClass($class);
                $phpdoc                = new DocBlock($reflection, new Context($reflection->getNamespaceName()));
                $this->cache[ $class ] = compact('reflection', 'phpdoc');
            } else {
                $reflection = $this->cache[ $class ][ 'reflection' ];
                $phpdoc     = $this->cache[ $class ][ 'phpdoc' ];
            }

//            $originalDoc = $reflection->getDocComment();
            /** @var \Illuminate\Support\Collection $content */
            $content = collect($phpdoc->getTagsByName($tagName))->map->getContent();
            $hasTag  = $content->filter(function ($content) use ($tagContent) {
                    return Str::contains($content, $tagContent);
                })->count() > 0;

            if ( ! $hasTag) {
                $phpdoc->appendTag(Tag::createInstance('@' . $tagName . ' ' . $tagContent, $phpdoc));
                if ( ! $phpdoc->getText()) {
                    $phpdoc->setText($reflection->getName());
                }
//                $this->writeDocBlock($reflection, $phpdoc);
            }
        }
        catch (ReflectionException $e) {
            // ignore
        }
    }


    protected function writeDocBlock(ReflectionClass $reflection, DocBlock $phpdoc, $originalDoc = null)
    {
        $serializer = new DocBlockSerializer();
        $serializer->getDocComment($phpdoc);
        $docComment = $serializer->getDocComment($phpdoc);

        $classname = $reflection->getShortName();
        $filename  = $reflection->getFileName();
        $type      = 'class';
        if ($reflection->isInterface()) {
            $type = 'interface';
        }
        $contents = file_get_contents($filename);

        if ($originalDoc) {
            $contents = str_replace($originalDoc, $docComment, $contents);
        } else {
            $needle  = "{$type} {$classname}";
            $replace = "{$docComment}\n{$type} {$classname}";
            $pos     = strpos($contents, $needle);
            if ($pos !== false) {
                $contents = substr_replace($contents, $replace, $pos, strlen($needle));
            }
        }
        if (file_put_contents($filename, $contents)) {
            $this->info('Written new phpDocBlock to ' . $filename);
        }
    }

}
