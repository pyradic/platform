import Vue, { ComponentOptions, PropOptions, WatchOptions } from 'vue'
import Component, { createDecorator, mixins } from 'vue-class-component'
import { componentFactory } from 'vue-class-component/lib/component'
import { InjectKey } from 'vue/types/options'
import { VueClass } from 'vue-class-component/lib/declarations';
import { interfaces } from 'inversify';
import debug from 'debug';
import { app } from '@c/Application';

const log = debug('decorators');

export * from './prop';
export type Constructor<T = any> = {
    new(...args: any[]): T
}
export { Component, Vue, mixins as Mixins, mixins }

/** Used for keying reactive provide/inject properties */
const reactiveInjectKey = '__reactiveInject__'

type InjectOptions = { from?: InjectKey; default?: any }

export function component<V extends Vue>(options: ComponentOptions<V> & ThisType<V> = {}): <VC extends VueClass<V>>(target: VC) => VC {
    if ( typeof options === 'function' ) {
        let Extended = componentFactory(options);
        return Extended as any
    }
    return function (Component) {
        let Extended = componentFactory(Component, options);
        if ( Component[ 'template' ] !== undefined ) {
            Extended = Extended.extend({ template: Component[ 'template' ] })
        }
        if ( Component[ 'components' ] !== undefined ) {
            Extended = Extended.extend({ components: Component[ 'components' ] })
        }
        if ( Component.prototype.setupLogger !== undefined ) {
            Extended = Extended.extend({ setupLogger: Component.prototype.setupLogger } as any)
        }
        return Extended as any
    };
}

export function inject(options?: InjectOptions | InjectKey) {
    return createDecorator((componentOptions, key) => {
        if ( typeof componentOptions.inject === 'undefined' ) {
            componentOptions.inject = {}
        }
        if ( ! Array.isArray(componentOptions.inject) ) {
            componentOptions.inject[ key ] = options || key
        }
    })
}

export function rinject(options?: InjectOptions | InjectKey) {
    return createDecorator((componentOptions, key) => {
        if ( typeof componentOptions.inject === 'undefined' ) {
            componentOptions.inject = {}
        }
        if ( ! Array.isArray(componentOptions.inject) ) {
            const fromKey    = !! options ? (options as any).from || options : key
            const defaultVal = (!! options && (options as any).default) || undefined
            if ( ! componentOptions.computed ) componentOptions.computed = {}
            componentOptions.computed![ key ]            = function () {
                const obj = (this as any)[ reactiveInjectKey ]
                return obj ? obj[ fromKey ] : defaultVal
            } as any
            componentOptions.inject[ reactiveInjectKey ] = reactiveInjectKey
        }
    })
}

export function provide(key?: string | symbol) {
    return createDecorator((componentOptions, k) => {
        let provide: any = componentOptions.provide
        if ( typeof provide !== 'function' || ! provide.managed ) {
            const original  = componentOptions.provide
            provide         = componentOptions.provide = function (this: any) {
                let rv = Object.create(
                    (typeof original === 'function' ? original.call(this) : original) ||
                    null,
                )
                for ( let i in provide.managed ) rv[ provide.managed[ i ] ] = this[ i ]
                return rv
            }
            provide.managed = {}
        }
        provide.managed[ k ] = key || k
    })
}

export function rprovide(key?: string | symbol) {
    return createDecorator((componentOptions, k) => {
        let provide: any = componentOptions.provide
        if ( typeof provide !== 'function' || ! provide.managed ) {
            const original  = componentOptions.provide
            provide         = componentOptions.provide = function (this: any) {
                let rv                  = Object.create(
                    (typeof original === 'function' ? original.call(this) : original) ||
                    null,
                )
                rv[ reactiveInjectKey ] = {}
                for ( let i in provide.managed ) {
                    rv[ provide.managed[ i ] ] = this[ i ] // Duplicates the behavior of `@Provide`
                    Object.defineProperty(rv[ reactiveInjectKey ], provide.managed[ i ], {
                        enumerable: true,
                        get       : () => this[ i ],
                    })
                }
                return rv
            }
            provide.managed = {}
        }
        provide.managed[ k ] = key || k
    })
}

const reflectMetadataIsSupported = typeof Reflect !== 'undefined' && typeof Reflect.getMetadata !== 'undefined'

function isPromise(obj: any): obj is Promise<any> {
    return obj instanceof Promise || (obj && typeof obj.then === 'function')
}

function applyMetadata(options: PropOptions | Constructor[] | Constructor, target: Vue, key: string) {
    if ( reflectMetadataIsSupported ) {
        if (
            ! Array.isArray(options) &&
            typeof options !== 'function' &&
            typeof options.type === 'undefined'
        ) {
            options.type = Reflect.getMetadata('design:type', target, key)
        }
    }
}

export function model(event?: string, options: PropOptions | Constructor[] | Constructor = {}) {
    return (target: Vue, key: string) => {
        applyMetadata(options, target, key)
        createDecorator((componentOptions, k) => {
            (componentOptions.props || ((componentOptions.props = {}) as any))[
                k
                ]                  = options
            componentOptions.model = { prop: k, event: event || k }
        })(target, key)
    }
}


export type WatchDecorator = {
    (path: string, options?: WatchOptions)
}
export var watch: WatchDecorator & {
    immediate: (path: string, options?: WatchOptions) => PropertyDecorator
    deep: (path: string, options?: WatchOptions) => PropertyDecorator
}
watch           = function (path: string, options: WatchOptions = {}) {
    const { deep = false, immediate = false } = options

    return createDecorator((componentOptions, handler) => {
        if ( typeof componentOptions.watch !== 'object' ) {
            componentOptions.watch = Object.create(null)
        }

        const watch: any = componentOptions.watch

        if ( typeof watch[ path ] === 'object' && ! Array.isArray(watch[ path ]) ) {
            watch[ path ] = [ watch[ path ] ]
        } else if ( typeof watch[ path ] === 'undefined' ) {
            watch[ path ] = []
        }

        watch[ path ].push({ handler, deep, immediate })
    })
} as any
watch.immediate = function (path: string, options: WatchOptions = {}) {
    return watch(path, { ...options, immediate: true })
}
watch.deep      = function (path: string, options: WatchOptions = {}) {
    return watch(path, { ...options, immediate: true })
}


// Code copied from Vue/src/shared/util.js
const hyphenateRE = /\B([A-Z])/g
const hyphenate   = (str: string) => str.replace(hyphenateRE, '-$1').toLowerCase()

export function emit(event?: string) {
    return function (_target: Vue, key: string, descriptor: any) {
        key              = hyphenate(key)
        const original   = descriptor.value
        descriptor.value = function emitter(...args: any[]) {
            const emit = (returnValue: any) => {
                if ( returnValue !== undefined ) args.unshift(returnValue)
                this.$emit(event || key, ...args)
            }

            const returnValue: any = original.apply(this, args)

            if ( isPromise(returnValue) ) {
                returnValue.then(returnValue => {
                    emit(returnValue)
                })
            } else {
                emit(returnValue)
            }

            return returnValue
        }
    }
}

export function ref(refKey?: string) {
    return createDecorator((options, key) => {
        options.computed        = options.computed || {}
        options.computed[ key ] = {
            cache: false,
            get(this: Vue) {
                return this.$refs[ refKey || key ]
            },
        } as any
    })
}

export function inject$(identifier?: interfaces.ServiceIdentifier<any>) {
    return (proto:Vue, key:string)=> {
        let Type: any
        if (typeof Reflect !== 'undefined' && typeof Reflect.getMetadata === 'function') {
            Type = Reflect.getMetadata('design:type', proto, key)
        }

        return createDecorator((options, key) => {
            // (options.mixins || (options.mixins = [])).push({
            //     data: function () {
            //         return { [ key ]: app().get(identifier) }
            //     },
            // });


            // options.computed = options.computed || {}
            //
            // options.computed[key] = {
            //     cache:false,
            //     get: function(this: any) {
            //         return app().get(identifier || Type)
            //     }
            // }

            // options.computed = options.computed || {};
            // options.computed[ key ] = {
            //     cache: false,
            //     get(this: Vue) {return app.get(id) }
            // }
            //
            // [ 'header', 'left', 'right', 'footer' ].forEach(part => {
            // })
            // options.computed        = options.computed || {}
            // options.computed[ key ] = {
            //     cache: false,
            //     get(this: Vue) {
            //         return this.$app.get(id);
            //     }
            // } as any
        })(proto, key)
    }
}


