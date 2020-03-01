<?php /** @noinspection DisconnectedForeachInstructionInspection */
/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection SuspiciousAssignmentsInspection */

namespace Pyro\Platform\Ui\ControlPanel\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Support\Resolver;
use Anomaly\Streams\Platform\Ui\Button\ButtonCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\Command\BuildButtons;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\Command\BuildNavigation;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\Command\SetActiveNavigationLink;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Command\BuildSections;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Command\SetActiveSection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\ShortcutCollection;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Laradic\Support\MultiBench;
use Pyro\Platform\Ui\ControlPanel\Component\Button;
use Pyro\Platform\Ui\ControlPanel\Component\NavigationLink;
use Pyro\Platform\Ui\ControlPanel\Component\Section;
use Pyro\Platform\Ui\ControlPanel\ControlPanel;
use Pyro\Platform\Ui\ControlPanel\ControlPanelBuilder;
use Pyro\Platform\Ui\ControlPanel\ControlPanelStructure;
use ServerTiming;

class BuildControlPanelStructure
{
    use DispatchesJobs;

    protected $cache = true;

    /** @var ControlPanelStructure */
    public static $structure;

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

    public function handle(ModuleCollection $modules, Cache $cache, Guard $guard)
    {
        $cp = resolve(\Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanel::class);
        if(static::$structure === null || $this->cache === false) {
            \ServerTiming::start('control_panel_structure');
            $key     = "cp.structure.user.{$guard->id()}";
            $navKey  = $key . '.nav';
            $lastKey = $key . '.last';
            $last    = $cp->getNavigation()->map->getSlug()->implode(',');
            if ($this->cache && $cache->has($lastKey) && $cache->has($navKey) && $cache->get($lastKey) === $last) {
                ServerTiming::start('control_panel_structure.cache');
                $structure = $cache->get($navKey);
                ServerTiming::stop('control_panel_structure.cache');
            } else {
                $cache->forever($lastKey, $last);
                ServerTiming::start('control_panel_structure.build');
                $structure = $this->build($modules);
                ServerTiming::stop('control_panel_structure.build');
                $cache->forever($navKey, $structure->map->except(['asset','image']));
            }
            static::$structure = $structure;
            if ($activeLink = $cp->getNavigation()->active()) {
                if ($link = static::$structure->get($activeLink->getSlug())) {
                    $link[ 'active' ] = true;
                }
                if ($activeSection = $cp->getActiveSection()) {
                    if ($section = $link[ 'children' ]->get($activeSection->getSlug())) {
                        $section[ 'active' ] = true;
                    }
                }
            }
            ServerTiming::stop('control_panel_structure');

        }
        // the real cp
        return static::$structure;
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
    public function build(ModuleCollection $modules)
    {
        MultiBench::on('createControlPanelStructure')->start();
        $activeModule = $modules->enabled()->accessible()->active();
        if ($activeModule) {
            $activeModule->setActive(false);
        }
        $navigation = $this->buildNavigation();
        if ($activeModule) {
            $activeModule->setActive(true);
//            $navigation->get($activeModule->getNamespace())->setActive(true);
        }
        $structure = ControlPanelStructure::make($navigation)->mapWithKeys(function (NavigationLink $link) {
            return [ $link->getSlug() => $link ];
        })->map->toArray()->map('collect')->map(function (Collection $link) {
            $link->put('url',
                $link->get('url',$link->dataGet('attributes.href', ''))
            );
            $link[ 'children' ] = collect($link[ 'children' ])->map('collect')->map(function (Collection $section) {
                $section->put('url',
                    $section->get('url',$section->dataGet('attributes.href', ''))
                );
                $section[ 'children' ] = collect($section[ 'children' ])->map('collect')->map(function(Collection $button){
                    $button['title'] = $button['text'];
                    $button->put('url',
                        $button->get('url',$button->dataGet('attributes.href', ''))
                    );
                    return $button;
                })->values();
                return $section;
            })->values();
            return $link;
        });
        // same for section
        MultiBench::on('createControlPanelStructure')->stop();
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
                    return $item;
                },
                $builder->getNavigation())
        );
        dispatch_now(new BuildNavigation($builder));
        $builder->getControlPanel()->getNavigation()->each(function (NavigationLink $link) {
            $link->setKey($link->getSlug());
        });
//        dispatch_now(new SetActiveNavigationLink($builder));
        $this->buildSections($builder, $modules);
        $navigation = $builder->getControlPanel()->getNavigation();
        return $navigation;
    }

    protected function buildSections(ControlPanelBuilder $builder, ModuleCollection $modules)
    {
        /** @var NavigationLink $navigation */
        /** @var Module $module */
//        $active = $builder->getControlPanel()->getNavigation()->active();
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
                            /** @noinspection UnsupportedStringOffsetOperationsInspection */
                            $item[ 'sections' ][ $k ][ 'section' ] = Section::class;
                        }
                    }
                    return [ $itemKey => $item ];
                })->toArray());

            dispatch_now(new BuildSections($builder));
            $sections = $builder->getControlPanel()->getSections();
            $sections->each(function (Section $section) use ($navigation) {
                $section->setKey($navigation->getKey() . '::' . $section->getSlug());
            });
            $navigation->setChildren($sections);
            $this->buildButtons($builder, $modules);
            $navigation->setActive(false);
            $module->setActive(false);
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
            $builder->setButtons(collect($builder->getButtons())->mapWithKeys(
                function ($item, $itemKey = null) use ($section) {
                    if (is_string($item)) {
                        $itemKey = $item;
                        $item    = [];
                    }
                    $item[ 'key' ]    = $section->getKey() . '.' . (isset($item[ 'slug' ]) ? $item[ 'slug' ] : $itemKey);
                    $item['enabled'] =true;
                    return [ $itemKey => $item ];
                })->toArray());
            dispatch_now(new BuildButtons($builder));
            $buttons = $builder->getControlPanel()->getButtons()->toBase();
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
