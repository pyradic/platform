<?php

namespace Pyradic\Platform\Installer;


use Illuminate\Support\Collection;

/**
 * @property int[] $skip_steps
 * @property int   $start_from_step
 * @property bool  $ignore_exceptions
 */
class InstallerOptions extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct();
        $this->loadDefaults();
    }

    public function loadDefaults()
    {
        $this->items = [
            'skip_steps'        => [],
            'start_from_step'   => 1,
            'ignore_exceptions' => false,
        ];
        return $this;
    }

    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        return parent::__get($key);
    }


}
