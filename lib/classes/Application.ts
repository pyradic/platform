import { AsyncContainerModule, Container, interfaces } from 'inversify';
import { Dispatcher } from './Dispatcher';
import { BootstrapOptions, IAgent, IConfig, IServiceProvider, IServiceProviderClass } from '../interfaces';
import { SyncHook, SyncWaterfallHook } from 'tapable';
import { Config } from './Config';
import { ServiceProvider } from './ServiceProvider';
import Vue, { Component, ComponentOptions } from 'vue';
import { merge } from 'lodash';
import { Cookies, Storage } from '@u/storage';
import debug from 'debug';
import { AxiosStatic } from 'axios';

const log = require('debug')('classes:Application');

export interface Application {
    storage: Storage
    agent: IAgent
    http: AxiosStatic
    data: Config<any> & any
    cookies:Cookies
}


const defaultConfig: Partial<IConfig> = {
    prefix: 'py',
    debug : false,
    csrf  : null
};

export function loadConfigDefaults(): Config<IConfig> {
    if ( !app().isBound('config.defaults') ) {
        let cpmfog = new Config(defaultConfig);
        app().instance('config.defaults', cpmfog);
    }
    return app().get('config.defaults');
}


export function app<T = Application>(binding: string = null): T {
    if ( binding === null ) {
        return Application.instance as any;
    }
    return Application.instance.get<T>(binding);
}

export class Application extends Container {
    public hooks = {
        loadProviders      : new SyncHook<Array<IServiceProviderClass>>([ 'Providers' ]),
        loadedProviders    : new SyncHook<Array<IServiceProvider>>([ 'providers' ]),
        registerProviders  : new SyncHook<Array<IServiceProviderClass | IServiceProvider>>([ 'providers' ]),
        registeredProviders: new SyncHook<Array<IServiceProviderClass | IServiceProvider>>([ 'providers' ]),
        configure          : new SyncHook<IConfig>([ 'config' ]),
        configured         : new SyncHook<Config<IConfig>>([ 'config' ]),
        bootstrap          : new SyncHook<BootstrapOptions>([ 'options' ]),
        bootstrapped       : new SyncHook<BootstrapOptions>([ 'options' ]),
        boot               : new SyncHook(),
        booted             : new SyncHook(),
        start              : new SyncHook<typeof Vue>([ 'Root' ]),
        started            : new SyncHook<Vue>([ 'root' ]),
        error              : new SyncWaterfallHook<Error>([ 'error' ]),
        provider           : {
            load      : new SyncHook<IServiceProviderClass>([ 'Provider' ]),
            loaded    : new SyncHook<IServiceProvider>([ 'provider' ]),
            register  : new SyncHook<IServiceProviderClass | IServiceProvider>([ 'provider' ]),
            registered: new SyncHook<IServiceProvider>([ 'provider' ]),
            booting   : new SyncHook<IServiceProvider>([ 'provider' ]),
            booted    : new SyncHook<IServiceProvider>([ 'provider' ])
        },
        install            : new SyncHook<typeof Vue, any>([ 'vue', 'options' ]),
        installComponents  : new SyncWaterfallHook<Record<string, Component | typeof Vue | any>>([ 'components' ]),
        installed          : new SyncHook<typeof Vue, any>([ 'vue', 'options' ])
    };

    protected static _instance: Application;
    public static get instance(): Application {
        if ( this._instance === undefined ) {
            this._instance = new Application();
        }
        return this._instance;
    };


    protected loadedProviders: Record<string, IServiceProvider> = {};
    protected providers: Array<IServiceProvider>                = [];
    protected booted: boolean                                   = false;
    protected started: boolean                                  = false;
    protected shuttingDown                                      = false;

    public get config(): Config<IConfig> & IConfig {return this.get('config');}

    protected constructor() {
        super({
            defaultScope       : 'Singleton',
            autoBindInjectable : true,
            skipBaseClassChecks: false
        });
        if ( Application._instance !== undefined ) {
            throw new Error('Cannot create another instance of Application');
        }
        Application._instance = this;
        this.bind(Application).toConstantValue(this);
        this.alias(Application, 'app', true);
        this.bind('app').toConstantValue(this);
        this.bind('events').to(Dispatcher).inSingletonScope();
    }


    public async bootstrap(_options: BootstrapOptions, ...mergeOptions: BootstrapOptions[]) {
        let options: BootstrapOptions = merge({
            providers: [],
            config   : {}
        }, _options, ...mergeOptions);
        log('bootstrap', { options });
        this.hooks.bootstrap.call(options);
        await this.loadProviders(options.providers);
        this.configure(options.config);
        await this.registerProviders(this.providers);
        this.hooks.bootstrapped.call(options);
        return this;
    }

    protected async loadProviders(Providers: Array<IServiceProviderClass>) {
        log('loadProviders', { Providers });
        this.hooks.loadProviders.call(Providers);
        await Promise.all(Providers.map(async Provider => this.loadProvider(Provider)));
        this.hooks.loadedProviders.call(this.providers);
        return this;
    }


    public async loadProvider(Provider: IServiceProviderClass): Promise<IServiceProvider> {
        if ( Provider.name in this.loadedProviders ) {
            return this.loadedProviders[ Provider.name ];
        }
        log('loadProvider', { Provider });
        this.hooks.provider.load.call(Provider);
        let provider: any = new Provider(this);
        if ( 'configure' in provider && Reflect.getMetadata('configure', provider) !== true ) {
            const defaults = loadConfigDefaults();
            Reflect.defineMetadata('configure', true, provider);
            await provider.configure(defaults as any);
        }
        if ( 'providers' in provider && Reflect.getMetadata('providers', provider) !== true ) {
            Reflect.defineMetadata('providers', true, provider);
            await this.loadProviders(provider.providers);
        }
        this.loadedProviders[ Provider.name ] = provider;
        this.providers.push(provider);
        this.hooks.provider.loaded.call(provider);
        return provider;
    }

    protected configure(config: IConfig) {
        config = merge(loadConfigDefaults().raw(), config);
        this.hooks.configure.call(config);
        let instance = Config.proxied<IConfig>(config);
        this.instance('config', instance);
        this.hooks.configured.call(instance);
        return this;
    }

    protected async registerProviders(providers: Array<IServiceProviderClass | IServiceProvider> = this.providers) {
        this.hooks.registerProviders.call(providers);
        await Promise.all(this.providers.map(async Provider => this.register(Provider)));
        this.hooks.registeredProviders.call(providers);
        return this;
    }

    public register = async (Provider: IServiceProviderClass | IServiceProvider) => {
        log('register', { Provider });
        this.hooks.provider.register.call(Provider);
        let provider: IServiceProvider = Provider as IServiceProvider;
        if ( Provider instanceof ServiceProvider === false ) {
            provider = await this.loadProvider(Provider as IServiceProviderClass);
        }
        if ( 'register' in provider && Reflect.getMetadata('register', provider) !== true ) {
            Reflect.defineMetadata('register', true, provider);
            await this.loadAsync(new AsyncContainerModule(() => provider.register()));
        }
        this.providers.push(provider);
        this.hooks.provider.registered.call(provider);
        return this;
    };

    public boot = async () => {
        if ( this.booted ) {
            return this;
        }
        log('boot');
        this.booted = true;
        this.hooks.boot.call();
        for ( const provider of this.providers ) {
            if ( 'boot' in provider && Reflect.getMetadata('boot', provider) !== true ) {
                this.hooks.provider.booting.call(provider);
                Reflect.defineMetadata('boot', true, provider);
                await provider.boot();
                this.hooks.provider.booted.call(provider);
            }
        }
        this.hooks.booted.call();
        return this;
    };

    public startEnabled = true;

    public root: Vue;

    public Root: typeof Vue = Vue

    public extendRoot(options: ComponentOptions<Vue>) {
        this.Root = this.Root.extend(options)
        return this;
    }

    public createLog(namespace) {
        return debug(namespace)
    }

    public start = async (mountPoint: string | HTMLElement = null, options: ComponentOptions<Vue> = {}) => {
        if ( this.started ) {
            return;
        }
        if ( !this.startEnabled ) {
            log('startEnabled=false', 'Skipping start', this);
            return;
        }
        log('start', { mountPoint, options });
        this.started = true;
        this.hooks.start.call(Vue);
        this.root = new (Vue.extend({
            // template: '<div id="app"><slot></slot></div>',
            // render(h,ctx){     return h(this.$slots.default, this.$slots.default)
        }));
        this.root.$mount(mountPoint);
        await this.root.$nextTick()
        this.instance('root', this.root)
        this.hooks.started.call(this.root);

        return this;
    };

    public error = async (error: any): Promise<this> => {
        log('error', { error });

        error = this.hooks.error.call(error);
        throw error;
        // await this.errorHandler.handle(error);
        // return this;
    };

    addBindingGetter(id, key = null) {
        key        = key || id;
        const self = this;
        Object.defineProperty(this, key, {
            get(): any {return self.get(id);}
        });
    }

    //region: ioc
    public alias<T, A>(abstract: interfaces.ServiceIdentifier<T>, alias: interfaces.ServiceIdentifier<A>, singleton: boolean = false) {
        let binding = this.bind(alias).toDynamicValue(ctx => ctx.container.get(abstract as any));
        if ( singleton ) {
            binding.inSingletonScope();
        }
        return this;
    }

    protected bindIf<T>(id, override: boolean = false, cb: (binding: interfaces.BindingToSyntax<T>) => void): this {
        if ( this.isBound(id) && !override ) return this;
        cb(this.isBound(id) ? this.rebind(id) : this.bind(id));
        return this;
    }

    public dynamic<T>(id: interfaces.ServiceIdentifier<T>, cb: (app: Application) => T) {
        return this.bind(id).toDynamicValue(ctx => {
            let req = ctx.currentRequest;
            return cb(this);
        });
    }

    public singleton<T>(id: interfaces.ServiceIdentifier<T>, value: any, override: boolean = false): this {
        return this.bindIf(id, override, b => b.to(value).inSingletonScope());
    }

    public binding<T>(id: interfaces.ServiceIdentifier<T>, value: any):this{

        return this
    }

    public instance<T>(id: interfaces.ServiceIdentifier<T>, value: any, override: boolean = false): this {
        return this.bindIf(id, override, b => b.toConstantValue(value));
    }

    public ctxfactory<T, T2>(id: interfaces.ServiceIdentifier<T>, factory: ((ctx: interfaces.Context) => (...args: any[]) => T2)) {
        this.bind(id).toFactory(ctx => factory(ctx));
        return this;
    }

    public factory<T, T2>(id: interfaces.ServiceIdentifier<T>, factory: ((...args: any[]) => T2)) {
        this.bind(id).toFactory(ctx => factory);
        return this;
    }

    //endregion
}