///<reference path="vue.d.ts"/>
///<reference path="global.d.ts"/>
///<reference path="modules.d.ts"/>

import 'reflect-metadata';
import { merge } from 'lodash';
import { VuePlugin as Plugin } from './VuePlugin';
import Vue from 'vue';
import { LogConfig } from '@/interfaces';
import { AxiosStatic } from 'axios';
import { Application } from '@c/Application';

export * from './interfaces';
export * from './classes/Application';
export * from './classes/ServiceProvider';
export * from './classes/Dispatcher';
export * from './classes/Config';
export * from './classes/Collection';
export * from './PlatformServiceProvider';
export * from './decorators';
export * from './utils/registerComponents'

export { merge, Plugin };


export class Component extends Vue {
    $py: Application

    $http:AxiosStatic

    getFirstMatchingParent:<T extends Vue>(isMatch: (component: T) => boolean, shouldCancel?: (component: T) => boolean) => T | null

    getAllMatchingParents:<T extends Vue>(cb: (component: T) => boolean, shouldCancel?: (component: T, matches: T[]) => boolean)=> T[]

    $log: (...params: any[])=> this
    // $events: Dispatcher
    __log: LogConfig
    __setupLog: (setup: LogConfig) => void
}

export async function getTreeNode(){
    let treeNode = await import('./utils/tree-node')
    return treeNode
}