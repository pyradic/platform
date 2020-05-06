///<reference path="types/index.d.ts"/>
///<reference path="modules.d.ts"/>

import 'reflect-metadata';
import { merge } from 'lodash';
import { PlatformVuePlugin as Plugin } from './PlatformVuePlugin';
import { toJS } from '@u/toJS';
import Vue from 'vue'
if(DEV) {
    Vue.config.performance = true;
}
export * from './styling'
export * from './interfaces';
export * from './classes/Application';
export * from './classes/ServiceProvider';
export * from './classes/Dispatcher';
export * from './classes/Route';
export * from './classes/UrlBuilder';
export * from './classes/Config';
export * from './classes/Collection';
export * from './classes/VuePlugin';
export * from './classes/Component';
export * from './classes/TsxComponent';
export * from './PlatformServiceProvider';
export * from './decorators';
export * from './utils/registerComponents'
export * from './utils/observable'
export * from './utils/colors'
export * from './utils/eventEmitter'
export * from './utils/slot'
export * from './utils/objectKeyValueSwitch'
export * from './utils/general'
export * from './utils/kindOf'
export * from './utils/cash'
export * from './utils/cash.ensureClass'


export { merge, Plugin, toJS };
if ( DEV ) {
    window[ 'toJS' ] = toJS
}


export async function generateVueCompletion() {
    // const mod3 = await import(/* webpackChunkName: "generateVueCodeCompletion" */'@u/generateVueCodeCompletion')
    // const mod4 = await import(/* webpackChunkName: "utils" */'@u/utils')
    // const mod2 = await import('@u/scroll')
    const mod = await import('@u/generateVueCodeCompletion')
    // console.log({ mod,mod2,mod3,mod4 })
    console.log({ mod })
    const completion = mod.generateVueCodeCompletion();
    console.log({ completion })
    return completion;
}

