<?php

namespace Pyro\Platform\Entry;

use Pyro\ActivityLogModule\Activity\Traits\CausesActivity;
use Pyro\ActivityLogModule\Activity\Traits\LogsActivity;

class EntryModel extends \Anomaly\Streams\Platform\Entry\EntryModel
{
    use LogsActivity;
    use CausesActivity;
}
