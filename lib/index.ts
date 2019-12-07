///<reference path="vue.d.ts"/>
///<reference path="global.d.ts"/>
///<reference path="modules.d.ts"/>

import 'reflect-metadata';
import { merge } from 'lodash';
import { PlatformVuePlugin as Plugin } from './PlatformVuePlugin';
import { toJS } from '@u/toJS';


export * from './styling'
export * from './interfaces';
export * from './classes/Application';
export * from './classes/ServiceProvider';
export * from './classes/Dispatcher';
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
export * from './utils/general'
export * from './utils/kindOf'

export { merge, Plugin, toJS };
if ( DEV ) {
    window[ 'toJS' ] = toJS
}

export async function generateVueCompletion() {
    const mod = await import('@u/generateVueCodeCompletion')
    console.log({ mod })
    const completion = mod.generateVueCodeCompletion();
    console.log({ completion })
    return completion;
}

