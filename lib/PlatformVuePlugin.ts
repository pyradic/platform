import Vue              from 'vue';
import elementNlLang    from 'element-ui/lib/locale/lang/nl';
import ElementLocale    from 'element-ui/lib/locale';
import LogPlugin        from '@/plugins/log';
import HttpPlugin       from '@/plugins/http';
import { VuePlugin }    from '@c/VuePlugin';
import { Application }  from '@c/Application';
import { Script }       from '#/script';
import VueI18n          from 'vue-i18n';
import VueFunctionalApi from 'vue-function-api';
import {Plugin as FragmentPlugin} from 'vue-fragment';

const log = require('debug')('install');

export { PlatformVuePlugin };
export default class PlatformVuePlugin extends VuePlugin {
    static __installed = false;


    static install(_Vue: typeof Vue, opts: any = {}) {
        if ( this.__installed ) { return; }
        this.__installed = true;

        log('install', { _Vue, opts });

        this.app.hooks.install.call(_Vue, opts);

        _Vue.use(VueFunctionalApi, {});
        // _Vue.use(VueI18n)
        const i18n = this.app.get<VueI18n>('i18n');
        i18n.mergeLocaleMessage('nl', elementNlLang);
        ElementLocale.i18n((key, value) => i18n.t(key, value));

        _Vue.use(FragmentPlugin);
        _Vue.use(LogPlugin);
        _Vue.use(HttpPlugin);

        this.prefixAndRegisterComponents(_Vue, {
            'script': Script,
        });

        Object.defineProperty(_Vue.prototype, '$py', {
            get(): any {return Application.instance; },
        });
        this.app.extendRoot({
            i18n,
            data() {
                return this.$py.data.getClone();
            },
        });

        this.app.hooks.installed.call(_Vue, opts);
    }
}