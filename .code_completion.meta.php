<?php

namespace Illuminate\Support {

//
//    /**
//     *
//     *
//     * @mixin \Illuminate\Support\Collection
//     */
//    class Collection {
//        public function cut(array $names) {
//            return \Illuminate\Support\Collection::cut($names);
//        }
//    }
}

namespace Illuminate\Console {

    /** @mixin \Illuminate\Console\Command */
    class Command
    {
        /**
         * @see \EddIriarte\Console\Providers\SelectServiceProvider::boot()
         */
        public function select(string $message = '', array $options = [], bool $allowMultiple = true)
        {
            return [];
        }
    }
}

namespace Anomaly\Streams\Platform\Addon {

    /**
     * @method array getRouteWheres()
     * @method setRouteWheres(array $routeWheres)
     * @method setRoutes(array $routes)
     * @property array $routeWheres
     */
    class AddonServiceProvider {}
    class AddonCollection
    {
        public function disabled()
        {
            return $this;
        }
    }
}
