///<reference path="vue.d.ts"/>
///<reference path="global.d.ts"/>
///<reference path="modules.d.ts"/>

import 'reflect-metadata';
import { merge } from 'lodash';
import { PlatformVuePlugin as Plugin } from './PlatformVuePlugin';
import Vue from 'vue';
import { LogConfig } from '@/interfaces';
import { AxiosStatic } from 'axios';
import { Application } from '@c/Application';
import { toJS } from './utils/toJS';

export * from './styling/export'
export * from './interfaces';
export * from './classes/Application';
export * from './classes/ServiceProvider';
export * from './classes/Dispatcher';
export * from './classes/Config';
export * from './classes/Collection';
export * from './classes/VuePlugin';
export * from './PlatformServiceProvider';
export * from './decorators';
export * from './utils/registerComponents'
export * from './utils/observable'
export * from './utils/colors'

export { merge, Plugin, toJS };
if ( DEV ) {
    window[ 'toJS' ] = toJS
}

export async function generateVueCompletion() {
    const mod = await import('./utils/generateVueCodeCompletion')
    console.log({ mod })
    const completion = mod.generateVueCodeCompletion();
    console.log({ completion })
}


export class Component extends Vue {
    $py: Application

    $http: AxiosStatic

    getFirstMatchingParent: <T extends Vue>(isMatch: (component: T) => boolean, shouldCancel?: (component: T) => boolean) => T | null

    getAllMatchingParents: <T extends Vue>(cb: (component: T) => boolean, shouldCancel?: (component: T, matches: T[]) => boolean) => T[]

    $log: (...params: any[]) => this
    // $events: Dispatcher
    __log: LogConfig
    __setupLog: (setup: LogConfig) => void
}

export async function getTreeNode() {
    let treeNode = await import('./utils/tree-node')
    return treeNode
}