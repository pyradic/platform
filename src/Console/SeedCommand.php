<?php

namespace Pyro\Platform\Console;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Pyro\Platform\Database\Seeder;

class SeedCommand extends Command
{
    protected $signature = 'seed';

    protected $description = 'Database seeders';

    public function handle(UserRepositoryInterface $repository)
    {
        $regs = Seeder::$registered;
        $user = $repository->findByEmail(env('ADMIN_EMAIL'));
        auth()->loginUsingId($user->getId());

        $classes = [];
        $names   = [];
        foreach ($regs as $name => $reg) {
            $classes[] = $reg[ 'class' ];
        }
        $names = $this->choice('seeds', array_keys($regs), null, null, true);
        $names = Arr::wrap($names);
        foreach ($names as $name) {
            $reg = $regs[ $name ];
            if ($reg[ 'run' ] instanceof Closure) {
                $reg[ 'run' ]($this);
            }
            $this->call('db:seed', [ '--class' => $reg[ 'class' ] ]);
        }

        $this->info('Done');
    }
}
