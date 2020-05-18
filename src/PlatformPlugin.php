<?php

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;
use Pyro\Platform\Ui\Input;

class PlatformPlugin extends Plugin
{
    public function getFunctions()
    {
        return [

            new \Twig_SimpleFunction(
                'input_*',
                function ($name) {

                    if (!in_array($name, ['authorize','resolver','expression','parse','translate','render','hydrate','valuate'])) {
                        throw new \Exception('Function [input_' . $name . '] does not exist.');
                    }

                    return forward_static_call_array(
                        [Input::class,$name],
                        array_slice(func_get_args(), 1)
                        );
                }
            ),
        ];
    }

}
