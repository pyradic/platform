<?php

namespace Pyro\Platform\Routing;

class ResponseFactory extends \Illuminate\Routing\ResponseFactory
{
    public function view($view, $data = [], $status = 200, array $headers = [])
    {
        event('response.view', [$view,$data,$status,$headers]);
        return parent::view($view, $data, $status, $headers);
    }

}
