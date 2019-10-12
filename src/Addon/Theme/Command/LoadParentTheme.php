<?php

namespace Pyro\Platform\Addon\Theme\Command;

use Anomaly\Streams\Platform\Addon\AddonProvider;
use Anomaly\Streams\Platform\Addon\Theme\ThemeCollection;
use Anomaly\Streams\Platform\Application\Application;
use Anomaly\Streams\Platform\Asset\Asset;
use Anomaly\Streams\Platform\Image\Image;
use Anomaly\Streams\Platform\View\ViewTemplate;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class LoadParentTheme
{
    public function handle(
        Asset $asset,
        Image $image,
        Factory $view,
        Request $request,
        Repository $config,
        ThemeCollection $themes,
        ViewTemplate $template,
        Application $application,
        AddonProvider $provider
    )
    {
//        parent::handle($asset, $image, $view, $request, $config, $themes, $template, $application, $provider);


        if ($theme = $themes->current()) {
            if ($theme instanceof \Pyro\Platform\Addon\Theme\Theme && $theme->hasParent()) {
                $parent      = $themes->get($theme->getParent());
                $parentHints = [
                    $parent->getPath('resources/views'),
                    $application->getResourcesPath(
                        "addons/{$parent->getVendor()}/{$parent->getSlug()}-{$parent->getType()}/views/"
                    ),
                ];
//                trans()->getLocale()
                $view->addNamespace($theme->getNamespace(), $parentHints);
                $view->addNamespace('parent', $parentHints);
                $view->addNamespace('theme', $parentHints);
                $asset->addPath('theme', $theme->getPath('resources'));
                $image->addPath('theme', $theme->getPath('resources'));
                $template->set('parent', $parent);
            }

//            trans()->addNamespace('theme', $theme->getPath('resources/lang'));
//
//            $asset->addPath('theme', $theme->getPath('resources'));
//            $image->addPath('theme', $theme->getPath('resources'));
//
//            /**
//             * Add the theme to the view template
//             * so it can be easily accessed later.
//             */
//            $template->set('theme', $theme);
        }
    }

}
