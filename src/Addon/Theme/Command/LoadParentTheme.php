<?php

namespace Pyro\Platform\Addon\Theme\Command;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonProvider;
use Anomaly\Streams\Platform\Addon\Theme\ThemeCollection;
use Anomaly\Streams\Platform\Application\Application;
use Anomaly\Streams\Platform\Asset\Asset;
use Anomaly\Streams\Platform\Event\Ready;
use Anomaly\Streams\Platform\Image\Image;
use Anomaly\Streams\Platform\View\ViewTemplate;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Laradic\Support\FS;
use Symfony\Component\Finder\SplFileInfo;

class LoadParentTheme
{
    /** @var \Anomaly\Streams\Platform\Asset\Asset */
    private $asset;

    /** @var \Anomaly\Streams\Platform\Image\Image */
    private $image;

    /** @var \Illuminate\Contracts\View\Factory */
    private $view;

    /** @var \Illuminate\Http\Request */
    private $request;

    /** @var \Illuminate\Contracts\Config\Repository */
    private $config;

    /** @var \Anomaly\Streams\Platform\Addon\Theme\ThemeCollection */
    private $themes;

    /** @var \Anomaly\Streams\Platform\View\ViewTemplate */
    private $template;

    /** @var \Anomaly\Streams\Platform\Application\Application */
    private $application;

    /** @var \Anomaly\Streams\Platform\Addon\AddonProvider */
    private $provider;

    public function __construct(
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

        $this->asset       = $asset;
        $this->image       = $image;
        $this->view        = $view;
        $this->request     = $request;
        $this->config      = $config;
        $this->themes      = $themes;
        $this->template    = $template;
        $this->application = $application;
        $this->provider    = $provider;
    }

    public function handle(Ready $event)
    {
        if ($theme = $this->themes->current()) {
            if ($theme instanceof \Pyro\Platform\Addon\Theme\Theme && $theme->hasParent()) {
                $parent      = $this->themes->get($theme->getParent());

//                $themeFiles = $this->getViewFiles($theme);
//                $parentFiles = $this->getViewFiles($parent);
//
//                $overrides = $themeFiles->filter(function(SplFileInfo $file, $path) use ($parentFiles){
//                    return $parentFiles->has($path);
//                });

                $parentHints = [
                    $parent->getPath('resources/views'),
                    $this->application->getResourcesPath(
                        "addons/{$parent->getVendor()}/{$parent->getSlug()}-{$parent->getType()}/views/"
                    ),
                ];
                $this->view->addNamespace($theme->getNamespace(), $parentHints);
                $this->view->addNamespace('parent', $parentHints);
                $this->view->addNamespace('theme', $parentHints);

                $this->asset->addPath('parent', $parent->getPath('resources'));
                $this->asset->addPath('theme', $theme->getPath('resources'));

                $this->image->addPath('parent', $parent->getPath('resources'));
                $this->image->addPath('theme', $theme->getPath('resources'));

                $this->template->set('parent', $parent);
            }

        }
    }

    /**
     * @param \Anomaly\Streams\Platform\Addon\Addon $addon
     * @param string                                $path
     *
     * @return \Illuminate\Support\Collection|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getViewFiles(Addon $addon, $path='resources/views')
    {
        $files=FS::allFiles($addon->getPath($path));
        $files = collect($files)->mapWithKeys(function(SplFileInfo $file){
            return [$file->getRelativePathname() => $file];
        });
        return $files;
    }
}
