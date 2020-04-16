<?php

namespace Pyro\Platform\Ui\Traits;

use Anomaly\Streams\Platform\Ui\Icon\Icon;
use Anomaly\Streams\Platform\Ui\Icon\IconRegistry;

/**
 * Trait HasIcon
 *
 * @link   http://pyrocms.com/
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
trait HasIcon
{

    /**
     * The icon to display.
     *
     * @var string
     */
    protected $icon;

    /**
     * Get the icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the icon.
     *
     * @param string $icon = \Pyro\IdeHelper\Examples\IconExamples::all()[$any]
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Return icon HTML.
     *
     * @param null $class
     *
     * @return null|string
     */
    public function icon($class = null)
    {
        if ( ! $this->icon) {
            return null;
        }

        return (new Icon())
            ->setType(app(IconRegistry::class)->get($this->icon))
            ->setClass($class);
    }

    public function hasIcon()
    {
        return ! ! $this->icon;
    }
}
