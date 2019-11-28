<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Ui\ControlPanel\Event\ControlPanelWasBuilt;
use Anomaly\Streams\Platform\Ui\Form\Event\FormWasBuilt;

class SetSafeDelimiters
{
    public static $safeDelimiterTypes = [
        'anomaly.field_type.editor',
    ];
    public function handle(FormWasBuilt $event)
    {
        /** @var \Illuminate\Support\Collection $types */

//        $builder = $event->getBuilder();
//        $form = $builder->getForm();
//        $types = $form->getFields()->toBase()->map->getType();
        $types= $event->getBuilder()->getFormFields()->toBase()->map->getType();
        if($types->intersect(static::$safeDelimiterTypes)->isNotEmpty()){
            platform()->config()->useSafeDelimiters();
        }
    }
}
