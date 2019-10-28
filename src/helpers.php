<?php


if(!function_exists('platform')){
    /**
     * @return \Pyro\Platform\Platform
     */
    function platform(){
        return app('platform');
    }
}
