<?php

namespace Pyro\Platform\Console;

use Anomaly\UsersModule\User\UserModel;
use Illuminate\Console\Command;

class UserPasswordCommand extends Command
{
    protected $signature = 'user:password {id?} {password?} {--email : Use email identification instead of ID} {--username : Use username identification instead of ID}';

    protected $description = 'Set user password';

    public function handle()
    {
        $id          = $this->argument('id');
        $useEmail    = $this->option('email');
        $useUsername = $this->option('username');

        if ($useEmail) {
            $id    = $id ?? $this->ask('email');
            $query = UserModel::whereEmail($id);
        } elseif ($useUsername) {
            $id    = $id ?? $this->ask('username');
            $query = UserModel::whereUsername($id);
        } else {
            $id    = $id ?? $this->ask('id');
            $query = UserModel::whereId($id);
        }

        $user = $query->get()->first();

        $password = $this->argument('password');

        if ( ! $password) {
            $password = $this->secret('password');
        }

        $user->password = $password;

        $this->info('Password set for ' . $user->display_name);
    }
}
