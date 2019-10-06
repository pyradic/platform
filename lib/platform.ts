///<reference path="vue.d.ts"/>
///<reference path="global.d.ts"/>
///<reference path="modules.d.ts"/>

import 'reflect-metadata';
import { merge } from 'lodash';
import { VuePlugin as Plugin } from './VuePlugin';

export * from './interfaces';
export * from './classes/Application';
export * from './classes/ServiceProvider';
export * from './classes/Dispatcher';
export * from './classes/Config';
export * from './classes/Collection';
export * from './PlatformServiceProvider';
export * from './decorators';

export { merge,Plugin };

