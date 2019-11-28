<?php

namespace Pyro\Platform;

use Illuminate\Contracts\Foundation\Application;
use Laradic\Support\Dot;

class PlatformConfig extends Dot
{

    public function setDefaults(Application $app)
    {
        $this->items = [
            'debug' => $app->config->get('app.debug'),
            'csrf'  => $app->make('session')->token(),
        ];
        return $this;
    }

    public function useSafeDelimiters()
    {
        $this->set('delimiters', ['{{{', '}}}']);
        return $this;
    }

}
