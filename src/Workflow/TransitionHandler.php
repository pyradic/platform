<?php

namespace Pyro\Platform\Workflow;

interface TransitionHandler
{
    public function hasScreen();
    public function renderScreen();
    /** @return \Pyro\Platform\Workflow\TransitionInterface */
    public function getTransition();
    public function validate(array $data = []);
    public function transit();

}
