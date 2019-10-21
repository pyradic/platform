<?php

namespace Pyro\Platform\Console;

class EnvSet extends \Anomaly\Streams\Platform\Application\Console\EnvSet
{
    public function handle()
    {
        /** @var \Jackiedo\DotenvEditor\DotenvEditor $de */
        $de = $this->getLaravel()->make('dotenv-editor');

        $line = $this->argument('line');

        list($variable, $value) = explode('=', $line, 2);

        $de->load();
        $de->setKey($variable, $value);
        $de->save();
    }
}
