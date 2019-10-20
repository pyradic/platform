<?php

namespace Pyro\Platform\Console;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
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
        foreach ($regs as $name => $reg) {
            $classes[] = $reg[ 'class' ];
        }
        $choices = $this->choice('seeds', $classes, null, null, true);
        $choices = Arr::wrap($choices);
        foreach ($choices as $class) {
            $this->call('db:seed', [ '--class' => $class ]);
        }

        $this->info('Done');

    }
}
