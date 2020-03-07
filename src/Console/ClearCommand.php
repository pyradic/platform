<?php

namespace Pyro\Platform\Console;

use Illuminate\Console\Command;

class ClearCommand extends Command
{
    protected $signature = 'clear';

    protected $description = 'clear all stuff';

    public function handle()
    {
        $this->clear('clear-compiled', 'Remove the compiled class file');
        $this->clear('assets:clear', 'Clear compiled public assets.');
//        $this->clear('auth:clear-resets', 'Flush expired password reset tokens');
        $this->clear('cache:clear', 'Flush the application cache');
        $this->clear('config:clear', 'Remove the configuration cache file');
        $this->clear('event:clear', 'Clear all cached events and listeners');
        $this->clear('httpcache:clear', 'Clear the entire HttpCache');
//        $this->clear('log-viewer:clear', 'Clear all generated log files');
        $this->clear('optimize:clear', 'Remove the cached bootstrap files');
        $this->clear('route:clear', 'Remove the route cache file');
        $this->clear('view:clear', 'Clear all compiled view files');
    }

    protected function clear($command, $description = null)
    {
        $this->info($description);
        $this->call($command);
    }
}
