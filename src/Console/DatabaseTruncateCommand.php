<?php

namespace Pyro\Platform\Console;

use DB;
use Illuminate\Console\Command;

class DatabaseTruncateCommand extends Command
{
    protected $signature = 'db:truncate';

    protected $description = 'Empty the database';

    public function handle()
    {
        $schema = DB::getDoctrineSchemaManager();
        $tables = $schema->listTables();

        if ($this->confirm('This will drop all tables', true)) {
            foreach ($tables as $table) {
                $this->info(" - Dropping table: <comment>{$table->getName()}</comment>");
                $schema->dropTable($table->getName());
            }
        }
        $this->info('Database truncated');
    }
}
