<?php

namespace Pyradic\Platform\Components;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Pyradic\Platform\Components\Service\ComponentPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OlveneerTwigComponentsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ComponentPass());
    }
}
