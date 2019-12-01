<?php

namespace Pyro\Platform\View;

class Loader extends \Anomaly\Streams\Platform\View\Twig\Loader
{
    protected function getOverloadPath($name)
    {
        /*
         * This method override makes ViewOverrides able to use `theme` namespace
         * `'theme::<view>' => '<override>'`
         */


        /*
         * We can only overload namespaced
         * views right now.
         */
        if (!str_contains($name, '::')) {
            return null;
        }

        // Normalize the name.
        $name = str_replace(['/', '\\'], '.', $name);

        /**
         * Pull mobile override.
         *
         * @TODO move this to merge once. self::cache
         */
        $mobile = array_merge(
            $this->mobiles->all(),
            config('streams.mobile', [])
        );

        /**
         * Pull standard override.
         *
         * @TODO move this to merge once. self::cache
         */
        $overrides = array_merge(
            $this->overrides->all(),
            config('streams.overrides', [])
        );

        /**
         * Normalize the theme:: shortcut prefix.
         */
//        $name = str_replace('theme::', $this->theme->getNamespace() . '::', $name);

        /**
         * If the override
         */
        if ($this->mobile && $path = array_get($mobile, $name)) {
            return str_replace('theme::', $this->theme->getNamespace() . '::', $path);
        } elseif ($path = array_get($overrides, $name)) {
            return str_replace('theme::', $this->theme->getNamespace() . '::', $path);
        }

        return parent::getOverloadPath($name);
    }

}
