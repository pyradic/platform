<?php

namespace Pyro\Platform\Ui;

use Pyro\Platform\Livewire\Component;

class FooBar extends Component
{
    public $value = 'value';

    protected $name = 'pyro.platform.ui.foo-bar';

    public function render()
    {
        return view('platform::components/foo-bar');
    }
}
