<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Entry\Event\GatherParserData;

class SetParserStub
{
    public function handle(GatherParserData $event)
    {
        $event->getData()->put('template', file_get_contents(dirname(__DIR__) . '/Entry/entry.stub'));
    }
}
