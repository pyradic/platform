import { ServiceProvider } from '@c/ServiceProvider';
import { Storage,Cookies } from '@u/storage';
import Vue from 'vue';
import Agent from '@u/platform';
import PlatformVuePlugin from '@/PlatformVuePlugin';
import Axios, { AxiosRequestConfig } from 'axios';
import { Config } from '@c/Config';
import { styleVars } from '@/styling/export';

export class PlatformServiceProvider extends ServiceProvider {
    public register() {
        this.vuePlugin(PlatformVuePlugin)

        this.app.instance('styling', styleVars);
        this.app.addBindingGetter('styling')

        this.app.bind('storage').to(Storage).inSingletonScope().onActivation((context, storage: Storage) => {
            let [ local, session ] = Storage.defaultDrivers();
            storage.configure({
                drivers: [ local, session ],
                driver : local.name
            });
            return storage;
        });
        this.app.addBindingGetter('storage')
        this.app.singleton('cookies', Cookies)
        this.app.addBindingGetter('cookies')

        this.app.instance('agent', Agent)
        this.app.addBindingGetter('agent')



        this.app.instance('events', new Vue);
        this.app.addBindingGetter('events')

        this.app.instance('data', Config.proxied(PLATFORM_DATA));
        this.app.addBindingGetter('data')

        this.app.instance<AxiosRequestConfig>('http.config',{

        } as AxiosRequestConfig)
        this.app.dynamic('http',app => {
            Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                Axios.defaults.headers.common['X-CSRF-TOKEN'] = token['content'];
            } else {
                Axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = this.app.config.csrf;
            }
            return Axios.create(app.get('http.config'));
        })
        this.app.addBindingGetter('http')

    }
}
