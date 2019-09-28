import { AsyncContainerModule, Container } from 'inversify';
import { Dispatcher } from './Dispatcher';
import { IServiceProvider, IServiceProviderClass } from '../interfaces';
import { SyncHook, SyncWaterfallHook } from 'tapable';
import { Config } from './Config';
import { ServiceProvider } from './ServiceProvider';

const log                             = require('debug')('classes:Application');
const defaultConfig: Partial<IConfig> = {
    prefix: 'c',
};

export interface Application {

}

export namespace Application {
    export interface OutputConfig {
        enabled?: boolean

    }

    export interface AppConfig {
        name?: string
        version?: string
        description?: string
    }

    export interface Config {
        app?: AppConfig
    }

    export interface BootstrapOptions {
        config?: Application.Config
        providers?: Array<IServiceProviderClass>
    }
}

export function loadConfigDefaults(): Config<IConfig> {
    if ( ! app.isBound('config.defaults') ) {
        let cpmfog = new Config(defaultConfig);
        app.constant('config.defaults', cpmfog);
    }
    return app.get('config.defaults');
}


export function app<T = Application>(binding:string=null):T{
    if(binding === null) {
        return Application.instance as T
    }
    return Application.instance.get<T>(binding)
}

export class Application extends Container {
    public hooks = {
        loadProviders      : new SyncHook<Array<IServiceProviderClass>>([ 'Providers' ]),
        loadedProviders    : new SyncHook<Array<IServiceProvider>>([ 'providers' ]),
        registerProviders  : new SyncHook<Array<IServiceProviderClass | IServiceProvider>>([ 'providers' ]),
        registeredProviders: new SyncHook<Array<IServiceProviderClass | IServiceProvider>>([ 'providers' ]),
        configure          : new SyncHook<Application.Config>([ 'config' ]),
        configured         : new SyncHook<Config<Application.Config>>([ 'config' ]),
        bootstrap          : new SyncHook<Application.BootstrapOptions>([ 'options' ]),
        bootstrapped       : new SyncHook<Application.BootstrapOptions>([ 'options' ]),
        boot               : new SyncHook(),
        booted             : new SyncHook(),
        start              : new SyncWaterfallHook<typeof Vue>([ 'Root' ]),
        started            : new SyncHook<Vue>([ 'root' ]),
        error              : new SyncWaterfallHook<Error>([ 'error' ]),
        provider           : {
            load      : new SyncHook<IServiceProviderClass>([ 'Provider' ]),
            loaded    : new SyncHook<IServiceProvider>([ 'provider' ]),
            register  : new SyncHook<IServiceProviderClass | IServiceProvider>([ 'provider' ]),
            registered: new SyncHook<IServiceProvider>([ 'provider' ]),
            booting   : new SyncHook<IServiceProvider>([ 'provider' ]),
            booted    : new SyncHook<IServiceProvider>([ 'provider' ]),
        },
        install            : new SyncHook<typeof Vue, any>([ 'vue', 'options' ]),
        installComponents  : new SyncWaterfallHook<Record<string, Component | typeof Vue | any>>([ 'components' ]),
        installed          : new SyncHook<typeof Vue, any>([ 'vue', 'options' ]),
    };

    protected static _instance: Application;
    public static get instance(): Application {
        if ( this._instance === undefined ) {
            this._instance = new Application()
        }
        return this._instance;
    };


    protected loadedProviders: Record<string, IServiceProvider> = {}
    protected providers: Array<IServiceProvider> = [];
    protected booted: boolean                    = false;
    protected started: boolean                    = false;
    protected shuttingDown                       = false;

    protected constructor() {
        super({
            defaultScope      : 'Singleton',
            autoBindInjectable: true,
            skipBaseClassChecks: false,
        })
        if ( Application._instance !== undefined ) {
            throw new Error('Cannot create another instance of Application')
        }
        Application._instance = this;
        this.bind(Application).toConstantValue(this);
        this.alias(Application, 'app', true);
        this.bind('app').toConstantValue(this);
        this.bind('events').to(Dispatcher).inSingletonScope();
    }

    public async bootstrap(_options: Application.BootstrapOptions) {
        let options: Application.BootstrapOptions = {
            providers: [],
            ..._options,
            config   : _options.config || {},
        };
        this.hooks.bootstrap.call(options);
        await this.loadProviders(options.providers);
        this.configure(options.config);
        await this.registerProviders(this.providers);
        this.hooks.bootstrapped.call(options);
        return this;
    }

    protected async loadProviders(Providers: Array<IServiceProviderClass>) {
        this.hooks.loadProviders.call(Providers);
        await Promise.all(Providers.map(async Provider => this.loadProvider(Provider)));
        this.hooks.loadedProviders.call(this.providers);
        return this;
    }


    public async loadProvider(Provider: IServiceProviderClass): Promise<IServiceProvider> {
        if ( Provider.name in this.loadedProviders ) {
            return this.loadedProviders[ Provider.name ]
        }
        this.hooks.provider.load.call(Provider);
        let provider = new Provider(this);
        if ( 'configure' in provider && Reflect.getMetadata('configure', provider) !== true ) {
            const defaults = loadConfigDefaults();
            Reflect.defineMetadata('configure', true, provider);
            await provider.configure(defaults as any);
        }
        if ( 'providers' in provider && Reflect.getMetadata('providers', provider) !== true ) {
            Reflect.defineMetadata('providers', true, provider);
            await this.loadProviders(provider.providers)
        }
        this.loadedProviders[ Provider.name ] = provider;
        this.providers.push(provider);
        this.hooks.provider.loaded.call(provider);
        return provider;
    }

    protected configure(config: Application.Config) {
        config = merge(loadConfigDefaults().raw(), config);
        this.hooks.configure.call(config);
        let instance = Config.proxied<Application.Config>(config);
        this.constant('config', instance);
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
    }

    public boot = async () => {
        if ( this.booted ) {
            return this;
        }
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
        return this
    };

    public startEnabled = true;

    public start = (mountPoint: string | HTMLElement = null, options: ComponentOptions<Vue> = {}) => {
        if ( this.started ) {
            return;
        }
        if ( ! this.startEnabled ) {
            log('startEnabled=false', 'Skipping start', this);
            return;
        }
        this.started = true;
        let Root     = this.hooks.start.call(this.component)
        if ( this.storage.get('preview', false) ) {
            Root = (Root.extend({ render: (h: CreateElement) => h(CPreview, { props: { menu: window[ 'preview_menu' ] } }) }))
        }
        Root      = this.hooks.start.call(Root);
        this.root = new Root(options);
        this.bind('root').toConstantValue(this.root);
        this.root.$mount(mountPoint);
        this.hooks.started.call(this.root);
    }

    public error = async (error: any): Promise<this> => {
        error = this.hooks.error.call(error);
        throw error;
        // await this.errorHandler.handle(error);
        // return this;
    };


    //region: ioc
    public alias<T, A>(abstract: interfaces.ServiceIdentifier<T>, alias: interfaces.ServiceIdentifier<A>, singleton: boolean = false) {
        let binding = this.bind(alias).toDynamicValue(ctx => ctx.container.get(abstract as any));
        if ( singleton ) {
            binding.inSingletonScope();
        }
        return this;
    }

    protected bindIf<T>(id, override: boolean = false, cb: (binding: interfaces.BindingToSyntax<T>) => void): this {
        if ( this.isBound(id) && ! override ) return this;
        cb(this.isBound(id) ? this.rebind(id) : this.bind(id));
        return this;
    }

    public dynamic<T>(id: interfaces.ServiceIdentifier<T>, cb: (app: Application) => T) {
        return this.bind(id).toDynamicValue(ctx => {
            let req = ctx.currentRequest;
            return cb(this)
        })
    }

    public singleton<T>(id: interfaces.ServiceIdentifier<T>, value: any, override: boolean = false): this {
        return this.bindIf(id, override, b => b.to(value).inSingletonScope());
    }

    public constant<T>(id: interfaces.ServiceIdentifier<T>, value: any, override: boolean = false): this {
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