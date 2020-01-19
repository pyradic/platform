<?php

namespace Pyro\Platform\Console;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Illuminate\Console\Command;

class MacroCommand extends Command
{
    protected $signature = 'macro {macro?} {--l|list}';

    public function handle()
    {
        try {
            $user = resolve(UserRepositoryInterface::class)->findByEmail(env('ADMIN_EMAIL'));
            auth()->loginUsingId($user->getId());
        } catch (\Throwable $e){
            $this->warn($e->getMessage());
        }

        if ($this->option('list')) {
            $rows = [];
            foreach (config('platform.macros', []) as $key => $macro) {
                $row[] = [ $key, $macro[ 'desc' ] ];
            }
            return $this->table([ 'macro', 'description' ], $rows);
        }
        $key      = $this->argument('macro') ?: $this->choice('Select macro to run', array_keys(config('platform.macros', [])));
        $macro    = config('platform.macros.' . $key, []);
        $commands = data_get($macro, 'commands', []);

        foreach ($commands as $command) {
            try {
                $this->line('Calling ' . $command[0]);
                $this->call($command[ 0 ], $command[ 1 ]);
            } catch (\Throwable $e){
                $this->error($e->getMessage());
                if(!$this->confirm('Error was detected, continue?', true)){
                    return;
                }
            }
        }
    }
}
