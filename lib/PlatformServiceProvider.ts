import { ServiceProvider } from '@c/ServiceProvider';
import { Storage } from '@u/storage';
import Vue from 'vue';
import Platform from '@u/platform';
import Plugin from '@/VuePlugin';

export class PlatformServiceProvider extends ServiceProvider {
    public register(): any | Promise<any> {

        this.app.bind('storage').to(Storage).inSingletonScope().onActivation((context, storage: Storage) => {
            let [ local, session ] = Storage.defaultDrivers();
            storage.configure({
                drivers: [ local, session ],
                driver : local.name
            });
            return storage;
        });

        this.app.instance('platform', Platform)
        this.app.instance('events', new Vue);

        this.app.addBindingGetter('platform')
        this.app.addBindingGetter('storage')

        this.app.hooks.booted.tap('Platform', () => {
            // install(Vue)
            Vue.use(Plugin)
        })
    }
}
