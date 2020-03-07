<?php

namespace Pyro\Platform\Console;

use Illuminate\Console\Command;

class DatabaseExportCommand extends Command
{
    protected $signature = 'db:export';

    protected $description = '';

    public function handle()
    {
        $this->comment('Exporting "pyro" database to "pyro.sql"');
        $connection = config('database.default');
        $db         = $this->ask('Database', config("database.connections.{$connection}.database"));
        $filePath   = $this->ask('File to export', 'pyro.sql');
        `mysqldump -u root -ptest --host=localhost --protocol=tcp --port=3306 --default-character-set=utf8 --routines --events "{$db}" > {$filePath}`;
        $this->info('All done sire!');
    }
}
