<?php

if ( ! function_exists('platform')) {
    /**
     * @return \Pyro\Platform\Platform
     */
    function platform()
    {
        return app('platform');
    }
}

if ( ! function_exists('get_class_properties_as_array')) {
    /**
     * @param object $instance
     *
     * @return array
     */
    function get_class_properties_as_array(object $instance)
    {
        return dispatch_now(new \Pyro\Platform\Command\GetClassArray($instance));
    }
}
