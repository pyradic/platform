<?php

namespace Pyro\Platform\Console;

use File;
use Illuminate\Console\Command;

class DatabaseImportCommand extends Command
{
    protected $signature = 'db:import';

    protected $description = '';

    public function handle()
    {
        $this->comment('Importing "pyro.sql" to database "pyro_dusk"');

        $db = $this->ask('Database', config('database.connections.dusk.database'));
        if ($this->confirm('Truncate database?', true)) {
            `mysql -u root -ptest -e "DROP DATABASE {$db};"`;
            `mysql -u root -ptest -e "CREATE DATABASE {$db};"`;
        }
        $filePath = $this->ask('File to import', 'pyro.sql');
        `mysql -u root -ptest --host=localhost --protocol=tcp --port=3306 --default-character-set=utf8 --comments --database={$db} < {$filePath}`;
        if (File::exists(base_path('pyro.sql'))) {
            $this->comment('Deleting "pyro.sql"');
            File::delete(base_path('pyro.sql'));
        }
        $this->info('All done sire!');
    }
}
