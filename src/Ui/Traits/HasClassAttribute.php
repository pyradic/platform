<?php

namespace Pyro\Platform\Ui\Traits;


/**
 * Trait HasClassAttribute
 *
 * @link   http://pyrocms.com/
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
trait HasClassAttribute
{

    /**
     * The class attribtue.
     *
     * @var string
     */
    protected $class ;

    /**
     * Get the class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the class.
     *
     * @param array $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Return class HTML.
     *
     * @param string[] $class
     * @return null|string
     */
    public function class(...$class)
    {
        $class[] = $this->getClass();
        return trim(implode(' ', array_filter($class)));
    }
}
