import { Application } from '../classes/Application';

export * from './colors'
export * from './icons'
export * from './index'
export * from './platform.config.generated'
export * from './platform.data.generated'
export * from './platform.data'
export * from './streams.generated'
export * from './streams'








export interface LogConfig {
    prefix?: string
    suffix?: string
    ns?: string
    name?: string
    id?: string
    prepend?: any[]
    append?: any[]
}

export interface BootstrapOptions {
    config?: IConfig
    providers?: Array<IServiceProviderClass>
    data?: any
}

// export interface VuePy {
//     app:Application
//     storage:Storage
//     agent:IAgent
//     config:Config<IConfig> & IConfig
//     http:AxiosStatic
//     data:Config<any> & any
//     set?(name:string, obj:any)
// }

export type IServiceProviderClass = {
    new(app: Application): IServiceProvider

}

export interface IServiceProvider {
    app: Application

    providers?: IServiceProviderClass[]

    register?(): any | Promise<any>

    boot?(): any | Promise<any>

}

export interface IConfig {
    prefix?: string
    debug?: boolean
    csrf?: string
    delimiters?: [ string, string ]
}

export interface Macroable {
    extend?(name: string, extension: (this: this) => any): this

    macro?(name: string, macro: (this: this, ...args: any[]) => any): this
}

export interface Type<T> {
    new(...args: any[]): T;
}

/* static interface declaration */
export interface ComparableStatic<T> extends Type<Comparable<T>> {
    compare(a: T, b: T): number;
}

/* interface declaration */
export interface Comparable<T> {
    compare(a: T): number;
}

/* class decorator */
export function staticImplements<T>() {
    return (constructor: T) => {}
}

export type Styles = Partial<Record<keyof CSSStyleDeclaration, any>>


export interface ResizeSize {
    height: number
    width: number
}

export interface IAgentIs {
    name: string
    platform: string
    version: string
    versionNumber: number
    android?: boolean
    blackberry?: boolean
    bb?: boolean
    desktop?: boolean
    cros?: boolean
    ios?: boolean
    ipad?: boolean
    iphone?: boolean
    ipod?: boolean
    kindle?: boolean
    linux?: boolean
    mac?: boolean
    playbook?: boolean
    silk?: boolean
    chrome?: boolean
    opera?: boolean
    safari?: boolean
    win?: boolean
    mobile?: boolean
    winphone?: boolean
    ssr?: boolean
    opr?: boolean
    vivaldi?: boolean
    webkit?: boolean
    rv?: boolean
    iemobile?: boolean
    ie?: boolean
    edge?: boolean
    electron?: boolean
    chromeExt?: boolean
    cordova?: boolean

}

export interface IAgent {
    is?: IAgentIs
    has?: { touch: boolean, webStorage: boolean }
    within?: { iframe: boolean }
}


