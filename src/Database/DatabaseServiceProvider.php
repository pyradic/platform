<?php

namespace Pyro\Platform\Database;

use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
    }


    protected function registerSeeders()
    {
        \Pyro\Platform\Database\Seeder\UserSeeder::registerSeed('users');
    }

    public function boot()
    {
    }
}
