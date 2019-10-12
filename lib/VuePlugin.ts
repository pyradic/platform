import Vue from 'vue'
import lang from 'element-ui/lib/locale/lang/nl';
import locale from 'element-ui/lib/locale';
import { Application } from '@c/Application';
import LogPlugin from '@/plugins/log';
import HttpPlugin from '@/plugins/http';
import { prefixAndRegisterComponents } from '@u/registerComponents';
import { Script } from '#/script';

const log = require('debug')('install')

export { VuePlugin }
export default class VuePlugin {
    static __installed = false

    static install(_Vue: typeof Vue, opts: any = {}) {
        if ( this.__installed ) { return }
        this.__installed = true

        log('install', { _Vue, opts });
        const app = Application.instance
        app.hooks.install.call(_Vue, opts);

        locale.use(lang);

        _Vue.use(LogPlugin)
        _Vue.use(HttpPlugin, {
            csrf: app.config.csrf
        })
        prefixAndRegisterComponents(_Vue, {
            'script': Script
        })

        Object.defineProperty(_Vue.prototype, '$py', {
            get(): any {return app}
        })

        app.hooks.installed.call(_Vue, opts)
    }
}