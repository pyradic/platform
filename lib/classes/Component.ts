import Vue from 'vue';
import { Application } from '@c/Application';
import { AxiosStatic } from 'axios';
import { LogConfig } from '@/interfaces';


export class Component extends Vue {
    $py: Application

    $http: AxiosStatic

    getFirstMatchingParent: <T extends Vue>(isMatch: (component: T) => boolean, shouldCancel?: (component: T) => boolean) => T | null

    getAllMatchingParents: <T extends Vue>(cb: (component: T) => boolean, shouldCancel?: (component: T, matches: T[]) => boolean) => T[]

    $log: (...params: any[]) => this
    // $events: Dispatcher
    __log: LogConfig
    __setupLog: (setup: LogConfig) => void
    b():string
    b(element:string):string
    b(modifiers:object):string
    b(element:string,modifiers:object):string
    b(element:string|false,mixin:string):string
    b(...args):any {return ''}
}