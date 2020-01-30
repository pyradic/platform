import 'reflect-metadata';
import Vue from 'vue';
import { LogConfig } from '@/interfaces';
import { AxiosStatic } from 'axios';
import { Application } from './Application';

import * as tsx       from 'vue-tsx-support'
import { BemMethods } from '@pyro/admin-theme';
export interface TsxComponent extends BemMethods{

}
export class TsxComponent<P = {}> extends Vue {

    $py: Application

    $http: AxiosStatic

    getFirstMatchingParent: <T extends Vue>(isMatch: (component: T) => boolean, shouldCancel?: (component: T) => boolean) => T | null

    getAllMatchingParents: <T extends Vue>(cb: (component: T) => boolean, shouldCancel?: (component: T, matches: T[]) => boolean) => T[]

    $log: (...params: any[]) => this
    // $events: Dispatcher
    __log: LogConfig
    __setupLog: (setup: LogConfig) => void

}
