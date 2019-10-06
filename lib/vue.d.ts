import _Vue, { ComponentOptions } from 'vue';
import { VuePy } from './interfaces';

declare module 'vue/types/vue' {
    // Global properties can be declared
    // on the `VueConstructor` interface


    interface Vue {
        $py?: VuePy

        getFirstMatchingParent<T extends Vue>(isMatch: (component: T) => boolean, shouldCancel?: (component: T) => boolean): T | null

        getAllMatchingParents<T extends Vue>(cb: (component: T) => boolean, shouldCancel?: (component: T, matches: T[]) => boolean): T[]

        // $log(...params: any[]): this
        // $events: Dispatcher
        // __log: LogConfig
        // __setupLog(setup: LogConfig)
    }

    interface VueConstructor<V extends _Vue = _Vue> {
        // $app?: Application
        options?: ComponentOptions<_Vue> & {
            [ key: string ]: any
        }
        util: {
            defineReactive(obj, key?, val?, customSetter?, shallow?)
            extend(to, _from)
            mergeOptions(parent, child, vm)
            warn(msg, vm)
        }
        // $store?: Store
    }
}

// ComponentOptions is declared in types/options.d.ts
declare module 'vue/types/options' {
    interface ComponentOptions<V extends _Vue = _Vue> {
        // tore?: Store
        // setupLogger?(this: V, setup: LogConfig): LogConfig | void;
    }
}

