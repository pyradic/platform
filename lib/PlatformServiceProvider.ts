import { ServiceProvider }           from '@c/ServiceProvider';
import { Cookies, Storage }          from '@u/storage';
import Vue                           from 'vue';
import Agent                         from '@u/platform';
import PlatformVuePlugin             from '@/PlatformVuePlugin';
import Axios, { AxiosRequestConfig } from 'axios';
import { styleVars }                 from '@/styling/export';
import { store }                     from '@/store';
import { theme }                     from '@/styling/theme';
import VueI18n                       from 'vue-i18n';
import { observable, observe }       from '@u/observable';
import { Config }                    from '@c/Config';
import { toJS }                      from '@u/toJS';

export class PlatformServiceProvider extends ServiceProvider {
    public register() {
        this.vuePlugin(PlatformVuePlugin);

        
        this.app.instance('i18n', new VueI18n({
            fallbackLocale: 'en',
            locale        : 'nl',
        }));

        // @todo fix properly in backend
        if ( this.app.config.csrf === null ) {
            this.app.config.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        this.app.instance('styling.theme', theme);

        this.app.instance('styling', styleVars);
        this.app.addBindingGetter('styling');

        this.app.bind('storage').to(Storage).inSingletonScope().onActivation((context, storage: Storage) => {
            let [ local, session ] = Storage.defaultDrivers();
            storage.configure({
                drivers: [ local, session ],
                driver : local.name,
            });
            return storage;
        });
        this.app.addBindingGetter('storage');
        this.app.singleton('cookies', Cookies);
        this.app.addBindingGetter('cookies');

        this.app.instance('agent', Agent);
        this.app.addBindingGetter('agent');

        this.app.instance('store', store);
        this.app.addBindingGetter('store');

        this.app.instance('events', new Vue);
        this.app.addBindingGetter('events');

        this.app.instance<AxiosRequestConfig>('http.config', {
            credentials: 'same-origin',
            headers    : {
                'Content-Type'    : 'application/json',
                'Accept'          : 'text/html, application/xhtml+xml',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN'    : this.app.config.csrf || document.head.querySelector('meta[name="csrf-token"]')[ 'content' ],
                'X-Livewire'      : true,
            },
        });
        this.app.dynamic('http', app => {
            const http = Axios.create({
                ...app.get<AxiosRequestConfig>('http.config'),
                ...(app.config.http || {}),
            });
            return http;
        });
        this.app.addBindingGetter('http');


        this.app.dynamic('settings', app => {
            const storage   = app.get<Storage>('storage');
            const data: any = observable(storage.get('settings', {}, { seralization: true, compression: true })); // const data = new Observable(_data);
            const settings  = Config.proxied<any>(data);
            observe(data, change => {
                console.log('change', change.type, '::', change.name, change.newValue, change.object);
                storage.set('settings', settings.toJS(), { seralization: true, compression: true });
            });
            return settings;
        });
        this.app.addBindingGetter('settings');

    }
}
