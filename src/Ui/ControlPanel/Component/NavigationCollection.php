<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;


class NavigationCollection extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationCollection
{
    public function collect()
    {
        $navigation =  $this->toBase()->map(function($nav){
            $nav=collect($nav->toArray());
            $children = $nav->get('children')->map('collect');

            return $nav;
        });

        return $navigation;
    }
}
