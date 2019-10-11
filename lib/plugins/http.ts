import Vue from 'vue';
import Axios from 'axios';

const log = require('debug')('plugins:http:install');

export {HttpPlugin}
export default class HttpPlugin {
    static __installed: boolean = false;

    static install(_Vue: typeof Vue, opts: { csrf: string }) {
        log('install', { _Vue, opts });
        if ( this.__installed ) { return; }
        this.__installed = true;

        _Vue.prototype.$http                            = Axios;

    }
}
