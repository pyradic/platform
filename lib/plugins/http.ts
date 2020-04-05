import Vue             from 'vue';
import { AxiosStatic } from 'axios';
import { VuePlugin }   from '@c/VuePlugin';

const log = require('debug')('plugins:http:install');

export { HttpPlugin };
export default class HttpPlugin extends VuePlugin {
    static __installed: boolean = false;

    static install(_Vue: typeof Vue, opts?: any) {
        log('install', { _Vue, opts });
        if ( this.__installed ) { return; }
        this.__installed = true;

        _Vue.prototype.$http = this.app.get('http');

    }
}

declare module 'vue/types/vue' {
    interface Vue {
        $http: AxiosStatic
    }
}

