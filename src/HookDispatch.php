<?php

namespace Pyro\Platform;

class HookDispatch
{
    public $command;

    public $arguments = [];

    public $caller;

    public function __construct($command, array $arguments = [], $caller = null)
    {
        $this->command   = $command;
        $this->arguments = $arguments;
        $this->caller    = $caller;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function getCaller()
    {
        return $this->caller;
    }

    public function setCaller($caller)
    {
        $this->caller = $caller;
        return $this;
    }


}
