<?php

namespace Pyradic\Platform\Command;

use Pyradic\Platform\PlatformRc;

class GetPlatformRc
{
    public function handle()
    {
        $rc = new PlatformRc();
        if ($rc->fileExists()) {
            $rc->load();
        }
        return $rc;
    }
}
