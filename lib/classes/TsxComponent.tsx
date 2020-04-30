import 'reflect-metadata';
import { LogConfig } from '@/interfaces';

import * as tsx                from 'vue-tsx-support';
import { BemMethods }          from '@pyro/admin-theme';
import { ComponentProperties } from '@c/Component';
import { AxiosStatic }         from 'axios';

export interface TsxComponent<P = {}> extends ComponentProperties, tsx.Component<P>, BemMethods {
    $http: AxiosStatic
    __setupLog: (setup: LogConfig) => void
}

export class TsxComponent<P = {}> extends tsx.Component<P> {

}
