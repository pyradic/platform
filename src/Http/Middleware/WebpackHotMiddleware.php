<?php namespace Pyro\Platform\Http\Middleware;

use Barryvdh\Debugbar\LaravelDebugbar;
use Closure;
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

    /** @var \Illuminate\Config\Repository */
    protected $bundles;

    /**
     * Create a new middleware instance.
     *
     * @param Container       $container
     * @param LaravelDebugbar $debugbar
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->bundles   = config('webpack.bundles', []);
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyResponse(\Symfony\Component\HttpFoundation\Request $request, Response $response)
    {
        $content = $response->getContent();
        if (stristr($content, '<!--WEBPACK_HERE_PLEASE-->') === false) {
            return $response;
        }

        $renderedContent = '';
        foreach ($this->bundles as $bundleName => $assets) {
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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($this->bundles) || config('webpack.enabled', false) !== true) {
            return $next($request);
        }

        $response = $next($request);
        // Modify the response to add the Debugbar
        $this->modifyResponse($request, $response);

        return $response;
    }
}
