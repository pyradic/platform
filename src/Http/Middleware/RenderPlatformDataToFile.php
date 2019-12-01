<?php

namespace Pyro\Platform\Http\Middleware;

use Pyro\Platform\Platform;

class RenderPlatformDataToFile
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        platform()->renderToFile(storage_path('platform_json.data.json'), 'data');
        platform()->renderToFile(storage_path('platform_json.config.json'), 'config');
        platform()->renderToFile(storage_path('platform_json.root.json'), 'root');
        platform()->renderToFile(storage_path('platform_json.global.json'), 'global');
        return $response;
    }
}
