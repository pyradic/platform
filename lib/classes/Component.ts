import Vue, { ComponentOptions } from 'vue';
import { Application }           from '@c/Application';
import { AxiosStatic } from 'axios';
import { LogConfig }   from '@/interfaces';
import { BemMethods }  from '@pyro/admin-theme';

export {AxiosStatic}
export interface ComponentProperties {
    $py: Application
    $http: AxiosStatic
    getFirstMatchingParent: <T extends Vue>(isMatch: (component: T) => boolean, shouldCancel?: (component: T) => boolean) => T | null
    getAllMatchingParents: <T extends Vue>(cb: (component: T) => boolean, shouldCancel?: (component: T, matches: T[]) => boolean) => T[]
    $log: (...params: any[]) => this
    __log: LogConfig
    __setupLog: (setup: LogConfig) => void
}
export interface Component extends Vue, BemMethods,ComponentProperties {
    __setupLog: (setup: LogConfig) => void
}
export class Component extends Vue {
    static block?:string
    static template?:ComponentOptions<any>['template']
    static components?:ComponentOptions<any>['components']

}