import { prefixAndRegisterComponents, registerElementComponents } from '@u/registerComponents';
import Vue from 'vue';
import { Application } from '@c/Application';


export abstract class VuePlugin {
    // static __installed = false
    // static install(_Vue: typeof Vue, opts: any = {}) {
    //     if ( this.__installed ) return;
    //     this.__installed = true;
    // }

    static get app():Application{return Application.instance}
    static prefixAndRegisterComponents: typeof prefixAndRegisterComponents = prefixAndRegisterComponents
    static registerElementComponents: typeof registerElementComponents     = registerElementComponents
}