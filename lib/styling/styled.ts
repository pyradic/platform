import * as csx                from 'csx';
import { NestedCSSProperties } from 'typestyle/src/types';
import { style }               from 'typestyle';
import { theme as _theme }     from '@/styling/theme';

export interface StyleTheme {
    [ k: string ]: any
}

export type StylesConfigItemDict = NestedCSSProperties
export type StylesConfigItemFn = (styles: Record<string, StylesConfigItemDict>) => NestedCSSProperties
export type StylesConfigItem = StylesConfigItemDict | StylesConfigItemFn
export type StylesConfigItems = Record<string, StylesConfigItem>
export type StylesConfigDictItems = Record<string, StylesConfigItemDict> & {
    class?: (...name: string[]) => string
    theme?: StyleTheme
}
export type StylesConfig<THEME extends StyleTheme = StyleTheme> = (helpers: StylesConfigHelpersParam<THEME>) => StylesConfigItems

export interface StylesConfigHelpersParam<THEME extends StyleTheme = StyleTheme> {
    theme: THEME
    util: typeof csx
}

export interface StylesProp {
    styles: StylesConfigDictItems
    classes: Record<string, string>
}

export function styled(_stylesConfig: StylesConfig, theme: StyleTheme = _theme): StylesConfigDictItems {
    let stylesConfig: StylesConfigItems = _stylesConfig as any;
    if ( typeof stylesConfig === 'function' ) {
        stylesConfig = _stylesConfig({ theme, util: csx }) as any;
    }

    let proxy: StylesConfigDictItems = new Proxy<StylesConfigDictItems>(stylesConfig as any, {
        get(target: StylesConfigItems, p: string | number | symbol, receiver: any): any {
            if ( p.toString() === 'class' ) {
                return (...names) => names.map(name => style({ $debugName: name }, proxy[ name ])).join(' ');
            }
            if ( p.toString() === 'theme' ) {
                return theme;
            }
            let value = Reflect.get(target, p, receiver);

            if ( typeof value === 'function' ) {
                value = value(proxy);
            }
            return value;
        },
        set(target: StylesConfigDictItems, p: string | number | symbol, value: any, receiver: any): boolean {
            return Reflect.set(target, p, value, receiver);
        },
    });
    return proxy;
    // let styles: StylesConfigDictItems   = {};
    // let classes: Record<string, string> = {};
    //
    // let keys = Object.keys(stylesConfig);
    // for ( const key of keys ) {
    //     styles[ key ]  = proxy[ key ];
    //     classes[ key ] = style(proxy[ key ] as any);
    // }
    //
    // return { styles, classes };
}
