<?php namespace Pyro\Platform\Webpack;

use Barryvdh\Debugbar\LaravelDebugbar;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebpackHotMiddleware
{
    /**
     * The App container
     *
     * @var Container
     */
    protected $container;

    /** @var \Illuminate\Contracts\Config\Repository */
    protected $config;

    /**
     * Create a new middleware instance.
     *
     * @param Container       $container
     * @param LaravelDebugbar $debugbar
     */
    public function __construct(Container $container, Repository $config)
    {
        $this->container = $container;
        $this->config    = $config;

//        $this->bundles   = config('webpack.bundles', []);
//        $themes = collect(config('webpack.themes', []));
        /** @var \Anomaly\Streams\Platform\Addon\Theme\Theme $theme */
//        $theme = resolve(ThemeCollection::class)->active();
//        if($theme instanceof Theme){
//            $ns = $theme->getNamespace();
//            if($themes->has($ns)) {
//                $this->bundles = $themes->get($ns);
//            }
//
//        }
    }

    /**
     * Modify the response and inject the debugbar (or data in headers)
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyResponse($bundles, Response $response)
    {
        $content = $response->getContent();
        if (stristr($content, '<!--WEBPACK_HERE_PLEASE-->') === false) {
            return $response;
        }

        $renderedContent = '';
        foreach ($bundles as $bundleName => $assets) {
            foreach ($assets[ 'styles' ] as $style) {
                $renderedContent .= "\n<link rel='stylesheet' type='text/css' href='{$style}'></link>";
            }
            foreach ($assets[ 'scripts' ] as $script) {
                $renderedContent .= "\n<script src='{$script}'></script>";
            }
        }

        $pos = strripos($content, '<!--WEBPACK_HERE_PLEASE-->');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $renderedContent . substr($content, $pos);
        } else {
            $content = $content . $renderedContent;
        }

        // Update the new content and reset the content length
        $response->setContent($content);
        $response->headers->remove('Content-Length');
        return $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $enabled = $this->config->get('platform.webpack.enabled');

        if ($enabled !== true) {
            return $next($request);
        }
        $bundles = $this->getBundles();
        if (empty($bundles)) {
            return $next($request);
        }

        $response = $next($request);
        $this->modifyResponse($bundles, $response);
        return $response;
    }

    protected function getPath()
    {
        return base_path($this->config->get('platform.webpack.path'));
    }

    protected function getBundles()
    {
        $path = $this->getPath();
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        return [];
    }
}
