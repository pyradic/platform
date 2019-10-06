import Vue from 'vue'
import lang from 'element-ui/lib/locale/lang/nl';
import locale from 'element-ui/lib/locale';
import { Application } from '@c/Application';
import TestComp from '#/TestComp.vue';

const log = require('debug')('install')

export{VuePlugin}
export default class VuePlugin {
    static __installed = false
    static install(_Vue: typeof Vue, opts: any = {}) {

        log('install', { _Vue, opts });
        if ( this.__installed ) { return }
        this.__installed = true
        const app        = Application.instance
        app.hooks.install.call(_Vue, opts);


        locale.use(lang);

        _Vue.component('TestComp', TestComp);

        _Vue.prototype.$py = {
            app     : Application.instance,
            storage : Application.instance.storage,
            platform: Application.instance.platform,
            config  : Application.instance.config
        }

        app.hooks.installed.call(_Vue, opts)
    }
}