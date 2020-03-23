<?php

namespace Pyro\Platform\Workflow;

interface Screen
{

    public function setTransition(TransitionInterface $handler);

    public function render();
}
