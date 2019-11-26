<?php /** @noinspection SuspiciousAssignmentsInspection */

namespace Pyro\Platform\Ui\ControlPanel\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Support\Authorizer;
use Anomaly\Streams\Platform\Support\Resolver;
use Anomaly\Streams\Platform\Ui\Button\ButtonCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonInput;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\Command\BuildButtons;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\Command\BuildNavigation;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Command\BuildSections;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionFactory;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionInput;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\ShortcutCollection;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;
use Laradic\Support\Dot;
use Laradic\Support\MultiBench;
use Pyro\Platform\Ui\ControlPanel\Component\Button;
use Pyro\Platform\Ui\ControlPanel\Component\NavigationLink;
use Pyro\Platform\Ui\ControlPanel\Component\Section;
use Pyro\Platform\Ui\ControlPanel\ControlPanel;
use Pyro\Platform\Ui\ControlPanel\ControlPanelBuilder;

class BuildControlPanelStructure
{
    use DispatchesJobs;

    protected $cache = true;

    /** @var \Illuminate\Support\Collection */
    protected $structure;

    public function __construct(bool $cache = true)
    {
        $this->cache = $cache;
    }

    protected function createControlPanelBuilder($cp = null)
    {
        if ($cp === null) {
            $cp = new ControlPanel(
                collect(),
                new SectionCollection(),
                new ShortcutCollection(),
                new NavigationCollection());
        }
        return new ControlPanelBuilder($cp);
    }

    public function handle(Application $app)
    {
        $this->structure = collect();
        MultiBench::on('createControlPanelStructure')->start();
        $structure = $app->call([ $this, 'build' ]);
        MultiBench::on('createControlPanelStructure')->stop();
        $elapsed=MultiBench::on('createControlPanelStructure')->getElapsed();
        return $structure;
    }

    /**
     * @param \Illuminate\Contracts\Foundation\Application            $app
     * @param \Anomaly\Streams\Platform\Addon\Module\ModuleCollection $modules
     * @param \Anomaly\Streams\Platform\Support\Authorizer            $authorizer
     * @param \Illuminate\Contracts\Cache\Repository                  $cache
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function build(Application $app, ModuleCollection $modules, Authorizer $authorizer, Cache $cache, Guard $guard)
    {
        $builder=$this->createControlPanelBuilder();
        (new NavigationHandler)->handle($builder,$modules);
        $this->dispatchNow(new BuildNavigation($builder));
        $key     = "cp.structure.user({$guard->id()})";
        $navKey  = $key . '.nav';
        $lastKey = $key . '.last';
        $last    = $builder->getControlPanelNavigation()->map->getSlug()->implode(',');
        if ($this->cache && $cache->has($lastKey) && $cache->has($navKey) && $cache->get($lastKey) === $last) {
            return $cache->get($navKey);
        }
        $cache->forever($lastKey, $last);
        $structure = $cache->rememberForever($navKey, function () use ($modules, $authorizer, $cache) {
            $activeModule = $modules->enabled()->accessible()->active();
            if ($activeModule) {
                $activeModule->setActive(false);
            }
            $navigation = $this->buildNavigation();
            if ($activeModule) {
                $activeModule->setActive(true);
                $navigation->get($activeModule->getNamespace())->setActive(true);
            }
            $nav= \Pyro\Platform\Ui\ControlPanel\Component\NavigationCollection::make($navigation->all())->collect();
            $nav = collect($nav);
            $a=$nav->map('collect')->map->get('children')->map('collect');


        });
        return $structure;
    }

    protected function buildNavigation()
    {
        $modules = resolve(ModuleCollection::class);
        $builder = $this->createControlPanelBuilder();
        with(new NavigationHandler)->handle($builder, $modules);
        $builder->setNavigation(
            array_map(
                function ($item) {
                    $item[ 'link' ]     = NavigationLink::class;
                    $item[ 'children' ] = collect();
//                    $item[ 'title' ]      = trans($item[ 'title' ]);
//                    $item[ 'breadcrumb' ] = trans($item[ 'breadcrumb' ]);
                    return $item;
                },
                $builder->getNavigation())
        );
        dispatch_now(new BuildNavigation($builder));
        $builder->getControlPanel()->getNavigation()->each(function (NavigationLink $link) {
            $link->setKey($link->getSlug());
        });
        MultiBench::on('createControlPanelStructure')->mark('structure');
        $this->buildSections($builder, $modules);
        MultiBench::on('createControlPanelStructure')->mark('structure:end');
        $navigation= $builder->getControlPanel()->getNavigation();
return $navigation;
    }

    protected function buildSections(ControlPanelBuilder $builder, ModuleCollection $modules)
    {
        /** @var NavigationLink $navigation */
        /** @var Module $module */
        foreach ($builder->getControlPanel()->getNavigation() as $navigation) {
            $module = $modules->get($navigation->getSlug());
            $navigation->setActive(true);
            $module->setActive(true);
            $builder->getControlPanel()->setSections(new SectionCollection());
            with(new SectionHandler($modules, resolve(Resolver::class)))->handle($builder);
            $builder->setSections(collect($builder->getSections())->mapWithKeys(
                function ($item, $itemKey = null) {
                    if (is_string($item)) {
                        $itemKey = $item;
                        $item    = [];
                    }
                    $item[ 'section' ]  = Section::class;
                    $item[ 'children' ] = collect();
                    if (isset($item[ 'sections' ])) {
                        foreach ($item[ 'sections' ] as $k => $v) {
                            $item[ 'sections' ][ $k ][ 'section' ] = Section::class;
                        }
                    }
//                    $item[ 'title' ]      = trans($item[ 'title' ]);
//                    $item[ 'breadcrumb' ] = trans($item[ 'breadcrumb' ]);
                    return [ $itemKey => $item ];
                })->toArray());

            dispatch_now(new BuildSections($builder));
            $navigation->setActive(false);
            $module->setActive(false);
            $sections = $builder->getControlPanel()->getSections();
            $sections->each(function (Section $section) use ($navigation) {
                $section->setParent($navigation);
                $section->setKey($navigation->getKey() . '::' . $section->getSlug());
            });

            $navigation->setChildren($sections);
            MultiBench::on('createControlPanelStructure')->mark('buttons');
            $this->buildButtons($builder, $modules);
            MultiBench::on('createControlPanelStructure')->mark('buttons:end');
        }
    }

    protected function buildButtons(ControlPanelBuilder $builder, ModuleCollection $modules)
    {
        /** @var Module $module */
        /** @var Section $section */
        foreach ($builder->getControlPanel()->getSections() as $section) {
            $section->setActive(true);
            $builder->getControlPanel()->setButtons(new ButtonCollection());
            with(new ButtonHandler($modules, resolve(Resolver::class)))->handle($builder);
            $builder->setButtons($section->getButtons());
            $builder->setButtons(collect($builder->getButtons())->mapWithKeys(
                function ($item, $itemKey = null) use ($section) {
                    if (is_string($item)) {
                        $itemKey = $item;
                        $item    = [];
                    }
                    $item[ 'button' ] = Button::class;
                    $item[ 'key' ]    = $section->getKey() . '.' . (isset($item[ 'slug' ]) ? $item[ 'slug' ] : $itemKey);
                    $item[ 'parent' ] = $section;
//                    $item[ 'title' ]      = trans($item[ 'title' ]);
//                    $item[ 'breadcrumb' ] = trans($item[ 'breadcrumb' ]);
                    return [ $itemKey => $item ];
                })->toArray());
            dispatch_now(new BuildButtons($builder));
            $buttons = $builder->getControlPanel()->getButtons()->toBase();
            $b=$buttons->map->toArray();
            $section->setChildren($buttons);
            $section->setActive(false);
        }
    }

}

///**
// * @param \Anomaly\Streams\Platform\Addon\Module\ModuleCollection $modules
// * @param \Anomaly\Streams\Platform\Support\Authorizer            $authorizer
// * @param \Illuminate\Contracts\Cache\Repository                  $cache
// *
// * @return mixed
// * @throws \Psr\SimpleCache\InvalidArgumentException
// */
//public function build2(ModuleCollection $modules, Authorizer $authorizer, Cache $cache)
//{
//    $active  = $modules->active();
//    $builder = $this->createControlPanelBuilder();
//    $this->dispatchNow(new BuildNavigation($builder));
//    $key     = 'cpnav';
//    $navKey  = $key . '.nav';
//    $lastKey = $key . '.last';
//    $last    = $builder->getControlPanelNavigation()->map->getSlug()->implode(',');
//    $cache->delete($lastKey);
//    $cache->delete($navKey);
//    if ($cache->has($lastKey) && $cache->has($navKey) && $cache->get($lastKey) === $last) {
//        return $cache->get($navKey);
//    }
//    $cache->forever($lastKey, $last);
//    $structure = $cache->rememberForever($navKey, function () use ($builder, $modules, $authorizer, $cache) {
//        MultiBench::on('controlPanelStructure')->mark('structure');
//        $nav = new Dot();
//        $modules->toBase()->each->setActive(false);
//        foreach ($builder->getNavigation() as $navigation) {
//            $navigation[ 'breadcrumb' ] = trans($navigation[ 'breadcrumb' ]);
//            $navigation[ 'title' ]      = trans($navigation[ 'title' ]);
//            $navSlug                    = str_replace('.', '_', $navigation[ 'slug' ]);
//            $nav->set($navSlug, $navigation);
//            $builder->getControlPanel()->setSections(new SectionCollection());
//            /** @var Module $module */
//            $module = $modules->get($navigation[ 'slug' ]);
//            $module->setActive(true);
//            dispatch_now(new BuildSections($builder));
////                $builder->setSections($module->getSections());
//            resolve(SectionInput::class)->read($builder);
//            foreach ($builder->getSections() as $section) {
//                if ( ! $authorizer->authorize(array_get($section, 'permission'))) {
//                    continue;
//                }
//                $nav->set($navSlug . '.sections.' . $section[ 'slug' ], $section);
//                $section = resolve(SectionFactory::class)->make($section);
//                $builder->getControlPanel()->addSection($section);
//            }
//            /** @var SectionCollection|\Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Contract\SectionInterface[] $sections */
//            $sections = $builder->getControlPanelSections();
//            $sections->toBase()->each->setActive(false);
//            foreach ($sections as $section) {
//                $section->setActive(true);
//                $builder->setButtons($section->getButtons());
//                resolve(ButtonInput::class)->read($builder);
//                $section->setActive(false);
//                $buttons = $builder->getButtons();
//
//                $nav->set($navSlug . '.sections.' . $section->getSlug() . '.buttons', $buttons);
//            }
//            $module->setActive(false);
//        }
//        $modules->toBase()->each->setActive(false);
//        MultiBench::on('controlPanelStructure')->mark('structure:end');
//        return $nav;
//    });
//    $active->setActive(true);
//    return $structure;
//}
//
//protected function excludeArrays($array)
//{
//    $array = Arr::wrap($array);
//    $array = array_filter($array, function ($item) {
//        return ! is_array($item);
//    });
//    return $array;
//}
//
//public function makeRecursive($structure)
//{
//    $links = collect();
//    foreach ($structure as $navigationKey => $navigation) {
//        $link = collect($navigation)->except([ 'sections' ]);
////            $link[ 'title' ]    = $navigation[ 'title' ];
////            $link[ 'slug' ]     = $navigation[ 'slug' ];
//        $link[ 'type' ]     = 'navigation';
//        $link[ 'key' ]      = $navigationKey;
//        $link[ 'url' ]      = data_get($navigation, 'attributes.href');
//        $link[ 'children' ] = collect();
//
//        foreach (data_get($navigation[ 'sections' ], []) as $sectionKey => $section) {
//            // @todo fix sections in wrong  navigation like dashbaord en widgets in module blocks
//            if ( ! isset($section[ 'title' ])) {
//                continue;
//            }
//            $child               = collect($section)->except([ 'buttons' ]);
//            $child[ 'title' ]    = trans($section[ 'title' ]);
//            $child[ 'key' ]      = $navigationKey . '::' . $sectionKey;
//            $child[ 'type' ]     = 'section';
//            $child[ 'slug' ]     = data_get($section, 'slug', $sectionKey);
//            $child[ 'url' ]      = data_get($section, 'attributes.href');
//            $child[ 'children' ] = collect();
//
//            foreach (data_get($section[ 'buttons' ], []) as $buttonKey => $button) {
//                if (is_int($buttonKey)) {
//                    $buttonKey = $button[ 'slug' ];
//                }
//                $b                     = collect($button); //Arr::except($button, [ 'buttons' ]);
//                $b[ 'title' ]          = trans(data_get($button, 'text'));
//                $b[ 'key' ]            = $navigationKey . '::' . $sectionKey . '.' . $buttonKey;
//                $b[ 'slug' ]           = $buttonKey;
//                $b[ 'type' ]           = 'button';
//                $b[ 'url' ]            = data_get($button, 'attributes.href');
//                $child[ 'children' ][] = $b;
//            }
//            $link[ 'children' ][] = $child;
//        }
//        $links[] = $link;
//    }
//    return $links;
//}
//
///**
// * This is a function for use in combination with PHPStorms 'Deep Assoc Completion' plugin.
// * The result of this command can be annotated with
// * [at]var array $structure = \Pyro\AdminTheme\Command\GetRecursiveControlPanelStructure::example()
// */
//public static function example($i = null)
//{
//    $a = [
//        'title' => '',
//        'key'   => '',
//        'slug'  => '',
//        'url'   => '',
//        'type'  => '',
//    ];
//    /** @var \Illuminate\Support\Collection|array $children */
//    $children = [ $i => $a ];
//    /** @var \Illuminate\Support\Collection|array $b */
//    $b                                         = [ $i => array_merge($a, [ 'children' => $children ]) ];
//    $b[ $i ][ 'children' ][ $i ][ 'children' ] = $a;
//    return $b;
//}
