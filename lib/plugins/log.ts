import Vue from 'vue';

const log = require('debug')('plugins:http:install');

export { LogPlugin };
export default class LogPlugin {
    static __installed: boolean = false;

    static install(_Vue: typeof Vue, opts: { csrf: string }) {
        log('install', { _Vue, opts });
        if ( this.__installed ) { return; }
        this.__installed = true;

        _Vue.prototype.$log = function (...args) {
            let opts = this.__log;
            let log  = require('debug')(opts.prefix + opts.ns + opts.name + opts.id + opts.suffix);
            args     = [].concat(opts.prepend).concat(args).concat(opts.append);
            return log(...args);
        };

        const strats       = _Vue.config.optionMergeStrategies;
        strats.setupLogger = strats.beforeCreate;
        _Vue.config[ '_lifecycleHooks' ].push('setupLogger');

        _Vue.mixin({
            beforeCreate() {
                this[ '__log' ] = { suffix: '', prefix: '', name: '', id: '', ns: 'component:', prepend: [], append: [] };
            },
            created() {
                this[ '__log' ].name = this.$options.name;
            },
            beforeMount() {
                if ( Array.isArray(this.$options.setupLogger) ) {
                    let handlers: Function[] = Array.from(this.$options.setupLogger as any);
                    handlers.forEach(handler => {
                        let options = handler.call(this, this[ '__log' ]);
                        if ( typeof options === 'object' ) {
                            this[ '__log' ] = { ...this[ '__log' ], ...options };
                        }
                    });
                }
            },
        });
    }
}
