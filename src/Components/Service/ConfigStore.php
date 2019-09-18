<?php

namespace Pyradic\Platform\Components\Service;

/**
 * Holds the configurations to be injected
 *
 * Class ConfigStore
 * @package Olveneer\TwigComponentsBundle\Service
 */
class ConfigStore
{
    /**
     * @var string
     */
    public $componentDirectory;

    /**
     * ConfigStore constructor.
     * @param $componentDirectory
     */
    public function __construct($componentDirectory = null)
    {
        $this->componentDirectory = $componentDirectory ?: storage_path('components');
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return get_object_vars($this);
    }
}
