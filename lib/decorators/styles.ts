import 'reflect-metadata';
// noinspection ES6UnusedImports
import Vue, { PropOptions }                             from 'vue';
// noinspection ES6UnusedImports
import { createDecorator }                              from 'vue-class-component';
// noinspection ES6UnusedImports
import { MaterialColors }                               from '@/interfaces';
// noinspection ES6UnusedImports
import { style, types }                                 from 'typestyle';
// noinspection ES6UnusedImports
import * as csx                                         from 'csx';
// noinspection ES6UnusedImports
import { NestedCSSProperties }                          from 'typestyle/src/types';
import { app }                                          from '@c/Application';
import { styled, StylesConfig, StylesProp, StyleTheme } from '@/styling';

export function styles<COMPONENT extends Vue = Vue, THEME extends StyleTheme = StyleTheme>(config: StylesConfig<THEME>) {
    return (target: Vue, key?, index?) => {
        return createDecorator((options, key) => {
            options.computed        = options.computed || {};
            options.computed[ key ] = {
                cache: false,
                get(this: Vue) {
                    const resolved = styled(config, app.get<StyleTheme>('styling.theme'));

                    return resolved;
                },
            };
        })(target, key, index);
    };
}

