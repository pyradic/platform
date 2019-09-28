<?php

namespace Pyradic\Platform\Http;

use Pyradic\Platform\Command\GatherJavascriptData;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

class JavascriptController extends AdminController
{
    public function data()
    {
        $data = $this->dispatchNow(new GatherJavascriptData());
    }

}
