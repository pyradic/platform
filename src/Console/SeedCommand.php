<?php

namespace Pyro\Platform\Console;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Pyro\Platform\Database\Seeder;

class SeedCommand extends Command
{
    protected $signature = 'seed {names?*} {--list}';

    protected $description = 'Database seeders';

    public function handle(UserRepositoryInterface $repository)
    {
        $regs = collect(Seeder::$registered);
        try {
            $user = $repository->findByEmail(env('ADMIN_EMAIL'));
            auth()->loginUsingId($user->getId());
        } catch (\Throwable $e){
            $this->warn($e->getMessage());
        }
        if($this->option('list')){
            $rows = collect($regs)->map(function($reg){
                return [$reg['name'], $reg['description'], $reg['class']];
            })->toArray();
            return $this->table(['name','description','class'], $rows);
        }

        $names = $this->argument('names');
        if(!$names) {
            $names = $this->choice('seeds', $regs->keys()->toArray(), null, null, true);
            $names = Arr::wrap($names);
        }
        foreach ($names as $name) {
            $reg = $regs[ $name ];
            $this->call('db:seed', [ '--class' => $reg[ 'class' ] ]);
        }

        $this->info('Done');
    }
}
