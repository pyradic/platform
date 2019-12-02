import 'reflect-metadata';
// noinspection ES6UnusedImports
import Vue, { PropOptions } from 'vue';
// noinspection ES6UnusedImports
import { createDecorator } from 'vue-class-component';
// noinspection ES6UnusedImports
import { MaterialColors } from '@/interfaces';
// noinspection ES6UnusedImports
import { style, types } from 'typestyle'
import * as csx from 'csx'
import { NestedCSSProperties } from 'typestyle/src/types';
import { app } from '@c/Application';

export interface StyleTheme {
    // colors: MaterialColors
    util: typeof csx

    [ k: string ]: any
}

export type StylesConfigItemDict = NestedCSSProperties
export type StylesConfigItemFn = (styles: Record<string, StylesConfigItemDict>) => NestedCSSProperties
export type StylesConfigItem = StylesConfigItemDict | StylesConfigItemFn
export type StylesConfigItems = Record<string, StylesConfigItem>
export type StylesConfigDictItems = Record<string, StylesConfigItemDict>
export type StylesConfig<COMPONENT extends Vue = Vue, THEME extends StyleTheme = StyleTheme> = (helpers: StylesConfigHelpersParam<COMPONENT, THEME>) => StylesConfigItems

export interface StylesConfigHelpersParam<COMPONENT extends Vue = Vue, THEME extends StyleTheme = StyleTheme> {
    theme: THEME
    util: typeof csx
    self: COMPONENT
}

export interface StylesProp {
    styles: StylesConfigDictItems
    classes: Record<string, string>
}

export function styles<COMPONENT extends Vue = Vue, THEME extends StyleTheme = StyleTheme>(config: StylesConfig<COMPONENT, THEME>) {
    return (target: Vue, key?, index?) => {
        return createDecorator((options, key) => {
            options.computed        = options.computed || {}
            options.computed[ key ] = {
                cache: false,
                get(this: Vue) {
                    const resolved: StylesProp = resolveStyles(config, app().get<StyleTheme>('styling.theme'), this)

                    return resolved
                }
            }
        })(target, key, index)
    }
}


export function resolveStyles(_stylesConfig: StylesConfig, theme: StyleTheme, self: Vue): StylesProp {
    let stylesConfig: StylesConfigItems = _stylesConfig as any
    if ( typeof stylesConfig === 'function' ) {
        stylesConfig = _stylesConfig({ theme, util: csx, self }) as any
    }

    let proxy: StylesConfigDictItems    = new Proxy<StylesConfigDictItems>(stylesConfig as any, {
        get(target: StylesConfigItems, p: string | number | symbol, receiver: any): any {
            let value = Reflect.get(target, p, receiver)
            if ( typeof value === 'function' ) {
                value = value(proxy)
            }
            return value;
        }
    })
    let styles: StylesConfigDictItems   = {}
    let classes: Record<string, string> = {}

    let keys = Object.keys(stylesConfig);
    for ( const key of keys ) {
        styles[ key ]  = proxy[ key ]
        classes[ key ] = style(proxy[ key ] as any)
    }

    return { styles, classes };
}
