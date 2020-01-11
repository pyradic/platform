import Vue             from 'vue'
import Vuex            from 'vuex'
import lang            from 'element-ui/lib/locale/lang/nl';
import locale          from 'element-ui/lib/locale';
import LogPlugin       from '@/plugins/log';
import HttpPlugin      from '@/plugins/http';
import { VuePlugin }   from '@c/VuePlugin';
import { Config }      from '@c/Config';
import { Application } from '@c/Application';
import { Script }      from '#/script';
import Axios           from 'axios';
import { warn }        from '@u/general';

const log = require('debug')('install')

export { PlatformVuePlugin }
export default class PlatformVuePlugin extends VuePlugin {
    static __installed = false


    static install(_Vue: typeof Vue, opts: any = {}) {
        if ( this.__installed ) { return }
        this.__installed = true

        log('install', { _Vue, opts });

        this.app.hooks.install.call(_Vue, opts);

        locale.use(lang);

        _Vue.use(LogPlugin)
        _Vue.use(HttpPlugin)

        this.prefixAndRegisterComponents(_Vue, {
            'script': Script
        })

        Object.defineProperty(_Vue.prototype, '$py', {
            get(): any {return Application.instance }
        })

        this.app.extendRoot({
            data(){
                return this.$py.data.getClone()
            }
        })

        this.app.hooks.installed.call(_Vue, opts)
    }
}