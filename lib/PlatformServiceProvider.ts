import { ServiceProvider }           from '@c/ServiceProvider';
import { Cookies, Storage }          from '@u/storage';
import Vue                           from 'vue';
import Agent                         from '@u/platform';
import PlatformVuePlugin             from '@/PlatformVuePlugin';
import Axios, { AxiosRequestConfig } from 'axios';
import { styleVars }                 from '@/styling/export';
import { store }                     from '@/store';
import { theme }                     from '@/styling/theme';

export class PlatformServiceProvider extends ServiceProvider {
    public register() {
        this.vuePlugin(PlatformVuePlugin);

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

        this.app.instance<AxiosRequestConfig>('http.config', {} as AxiosRequestConfig);
        this.app.dynamic('http', app => {

            const http = Axios.create({
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'text/html, application/xhtml+xml',
                    'X-Requested-With':'XMLHttpRequest',
                    'X-CSRF-TOKEN': app.config.csrf || document.head.querySelector('meta[name="csrf-token"]')['content'],
                    'X-Livewire': true,
                },
                ...(app.config.http || {})
            })

            return http;
        });
        this.app.addBindingGetter('http');


    }
}
