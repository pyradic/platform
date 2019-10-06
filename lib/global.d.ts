import * as platform from './platform'
import {Plugin, Application } from './platform';

declare global {

    interface Window {
        pyro: { platform: typeof platform & {Application:typeof Application, Plugin:typeof Plugin} }

        PLATFORM_DATA: any
        PLATFORM_CONFIG: any
        PLATFORM_PROVIDERS: any
    }

    const PLATFORM_DATA: any
    const PLATFORM_CONFIG: any
    const PLATFORM_PROVIDERS: any
}
