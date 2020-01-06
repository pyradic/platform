import Vue             from 'vue';
import { Application } from '@c/Application';
import { AxiosStatic } from 'axios';
import { LogConfig }   from '@/interfaces';
import { BemMethods }  from '@pyro/admin-theme';


export interface Component extends Vue, BemMethods {}
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