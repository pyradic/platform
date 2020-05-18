<?php

namespace Pyro\Platform\Ui\ControlPanel\Command;

use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Ui\Button\ButtonCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\Command\BuildButtons;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\Command\BuildNavigation;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\Command\SetActiveNavigationLink;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Command\BuildSections;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Command\SetActiveSection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Section;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionHandler;
use Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder;
use Illuminate\Contracts\Foundation\Application;
use Pyro\Platform\Ui\ControlPanel\Component\Button;
use Pyro\Platform\Ui\ControlPanel\Component\ButtonEnabledGuesser;
use Pyro\Platform\Ui\ControlPanel\Component\NavigationNode;
use ServerTiming;

class TransformControlPanelNavigation
{
    /** @var NavigationNode */
    static $node;

    public function handle(Application $app, ModuleCollection $modules)
    {
        if (static::$node !== null) {
            return static::$node;
        }

        ServerTiming::start('buildcp');
        $app->when(\Pyro\Platform\Ui\ControlPanel\ControlPanel::class)
            ->needs(NavigationCollection::class)
            ->give(function () {
                return new NavigationCollection();
            });
        $app->when(\Pyro\Platform\Ui\ControlPanel\ControlPanel::class)
            ->needs(SectionCollection::class)
            ->give(function () {
                return new SectionCollection();
            });
        $app->when(\Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonGuesser::class)
            ->needs(\Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\Guesser\EnabledGuesser::class)
            ->give(function () {
                return new ButtonEnabledGuesser(resolve('request'));
            });
        $root = new NavigationNode();
//        $builder = new \Pyro\Platform\Ui\ControlPanel\ControlPanelBuilder(new ControlPanel());
        /** @var ControlPanelBuilder $builder */
        $builder = resolve(ControlPanelBuilder::class);
        dispatch_now(new BuildNavigation($builder));
        dispatch_now(new SetActiveNavigationLink($builder));
        $navigation = $builder->getControlPanelNavigation();

        if ($activeLink = $navigation->active()) {
            $activeLink->setActive(false);
        }
        if ($activeModule = $modules->active()) {
            $activeModule->setActive(false);
        }
        foreach ($navigation as $link) {
            $navigationMenuItem = $root->createChild($link);
            $navigationMenuItem->setKey($link->getSlug());

            $module = $modules->get($link->getSlug());
            $module->setActive(true);
            $link->setActive(true);

            $builder = resolve(ControlPanelBuilder::class);

            resolve(SectionHandler::class)->handle($builder);
            dispatch_now(new BuildSections($builder));
            if ($link === $activeLink) {
                dispatch_now(new SetActiveSection($builder));
            }

            $sections = $builder->getControlPanelSections();
            if ($activeSection = $sections->active()) {
                $activeSection->setActive(false);
            }

            $noParent = $sections->where('parent', null);
            foreach ($noParent as $slug => $section) {
                $section->setActive(true);
                $builder->setButtons($section->getButtons());
                $sectionMenuItem = $navigationMenuItem->createChild($section);
                $sectionMenuItem->setKey($link->getSlug() . '::' . $section->getSlug());
                $section->setButtons($buttons = $this->getSectionButtons($builder, $section));

                $children = $sections->children($section->getSlug());
                if ($children->isNotEmpty()) {
                    foreach ($children as $child) {
                        $subSectionMenuItem = $sectionMenuItem->createChild($child);
                        $subSectionMenuItem->setKey($link->getSlug() . '::' . $section->getSlug() . '.' . $child->getSlug());
                        $child->setButtons($buttons = $this->getSectionButtons($builder, $child));
                        $subSectionMenuItem->setChildren($buttons->map([$subSectionMenuItem, 'createChild']));
//                        foreach($buttons as $button){
//                            $buttonMenuItem = $subSectionMenuItem->createChild($button);
//                            $buttonMenuItem->setKey($link->getSlug() . '::' . $section->getSlug() . '.' . $child->getSlug() . '.' . $button->getSlug());
//                        }
                    }
                } else {
//                    foreach($buttons as $button){
//                        $buttonMenuItem = $sectionMenuItem->createChild($button);
//                        $buttonMenuItem->setKey($link->getSlug() . '::' . $section->getSlug() . '.' . $button->getSlug());
//                    }
                }
                $section->setActive(false);
            }
            if ($activeSection) {
                $activeSection->setActive(true);
            }

            $module->setActive(false);
            $link->setActive(false);
        }
        if ($activeLink) {
            $activeLink->setActive(true);
        }
        if ($activeModule) {
            $activeModule->setActive(true);
        }
        ServerTiming::stop('buildcp');
        return static::$node = $root;
    }

    protected function getSectionButtons(ControlPanelBuilder $builder, Section $section)
    {
        $section->setActive(true); //$bb = resolve(ButtonBuilder::class);
        $builder->setButtons($section->getButtons());
        app()->bind(\Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonBuilder::class, \Pyro\Platform\Ui\ControlPanel\Component\ButtonBuilder::class);
        dispatch_now(new BuildButtons($builder));
        app()->bind(\Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonBuilder::class, \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonBuilder::class);

        $buttons = $builder->getControlPanel()->getButtons();
        $sbuttons = $section->getButtons();
        foreach ($buttons as $button){
            if($slug = $button->getSlug()){
                $button->setKey($section->getKey() . '.' . $slug);
                $button->setSectionKey($section->getKey());
            }
        }
        $section->setActive(false);
        $builder->getControlPanel()->setButtons(new ButtonCollection());
        $buttons=$buttons->filter(function($button){
            return $button instanceof Button;
        });
        $builder->setButtons([]);
        return $buttons;
    }

}
