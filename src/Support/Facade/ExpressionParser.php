<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class ExpressionParser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'expression_parser';
    }
}
