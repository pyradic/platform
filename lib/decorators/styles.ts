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
import { app }                                                                                    from '@c/Application';
import { styled, StylesConfig, StylesConfigDictItems, StylesConfigItems, StylesProp, StyleTheme } from '@/styling';

export function styles<COMPONENT extends Vue = Vue, THEME extends StyleTheme = StyleTheme>(config: StylesConfig<COMPONENT, THEME>) {
    return (target: Vue, key?, index?) => {
        return createDecorator((options, key) => {
            options.computed        = options.computed || {}
            options.computed[ key ] = {
                cache: false,
                get(this: Vue) {
                    const resolved: StylesProp = styled(config, app.get<StyleTheme>('styling.theme'), this)

                    return resolved
                }
            }
        })(target, key, index)
    }
}

