<?php

namespace Pyro\Platform\Http;

use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Pyro\Platform\Command\GatherJavascriptData;

class JavascriptController extends AdminController
{
    public function data()
    {
        $data = $this->dispatchNow(new GatherJavascriptData());
    }

}
