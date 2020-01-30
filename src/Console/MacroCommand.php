<?php

namespace Pyro\Platform\Console;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Illuminate\Console\Command;
use Symfony\Component\ErrorHandler\ErrorRenderer\CliErrorRenderer;

class MacroCommand extends Command
{
    protected $signature = 'macro {macro?} {--l|list} {--s|show=}';

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
                $rows[] = [ $key, array_get($macro,  'desc','') ];
            }
            return $this->table([ 'macro', 'description' ], $rows);
        }
        if ($this->hasOption('show') && $this->option('show')) {
            $key      = $this->option('show') ?: $this->choice('Select macro to show', array_keys(config('platform.macros', [])));
            $commands    = config('platform.macros.' . $key.'.commands', []);
            $rows = [];
            foreach($commands as $command){
                $rows[] = [$command[0], $this->implodeArrayRecursive($command[1])];
            }
            return $this->table([ 'command', 'args' ], $rows);
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
                $choice = $this->choice('Error was detected', [
                    'continue' => 'Continue',
                    'render_continue' => 'Render error and continue',
                    'render_exit' => 'Render error and exit',
                    'exit' => 'Exit'
                ],'continue');
                if(starts_with($choice, 'render')){
                    $this->line((new CliErrorRenderer)->render($e)->getAsString());
                }
                if(ends_with($choice,'continue')){
                    continue;
                }
                return;
            }
        }
    }

    protected function implodeArrayRecursive($array ,$glue = '\', \''){
        $ret = '';

        foreach ($array as $key => $item) {
            if(is_string($key)) {
                $ret .= $key . "' => '";
            }
            if(is_array($item)) {
                $ret .= $this->implodeArrayRecursive($item, $glue) . $glue;

            } else {

                $ret .= $item . $glue;
            }
//                if (is_array($item)) {
//                    if(is_string($key)){
//                        $ret .= $key . "' => ";
//                    }
//                    $ret .= $this->implodeArrayRecursive($item, $glue) . $glue;
//                    $ret .= "'";
//                } else {
//                    if(is_string($key)){
//                        $ret .= "'" . $key . "' => '";
//                    }
//                    $ret .= $item . $glue;
//                }
        }

        $ret = substr($ret, 0, 0-strlen($glue));

        return "{$ret}";
    }
}
