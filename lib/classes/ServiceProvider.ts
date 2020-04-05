
import { Application } from './Application';
import { IServiceProvider } from '@/interfaces';
import Vue, { PluginFunction, PluginObject } from 'vue';
export abstract class ServiceProvider implements IServiceProvider {
    constructor(public readonly app: Application) {
    }

    vuePlugin<T>(plugin: PluginObject<T> | PluginFunction<T>, options?: T){
        this.app.hooks.booted.tap('Platform', () => {
            if(options === undefined){
                options={} as any
            }
            Vue.use(plugin, options)
        });
        return this
    }
}
