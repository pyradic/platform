import * as platform from './index'
import {Plugin, Application } from './index';

declare global {

    interface PyroExports { pyro__platform: typeof platform & {
        Application:typeof Application, Plugin:typeof Plugin} }
    interface Window {
        pyro: PyroExports

        PLATFORM_DATA: any
        PLATFORM_CONFIG: any
        PLATFORM_PROVIDERS: any
    }

    const pyro: PyroExports
    const PLATFORM_DATA: any
    const PLATFORM_CONFIG: any
    const PLATFORM_PROVIDERS: any
    const PROD: boolean
    const DEV: boolean
    const HOT: boolean
    const ENV: any
    const NAMESPACE: string
}
