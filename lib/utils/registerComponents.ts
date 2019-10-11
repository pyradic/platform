import { kebabCase } from 'lodash';
import Vue from 'vue';
import { app } from '@c/Application';


const log = require('debug')('utils:registerComponents');

export function prefixAndRegisterComponents(_Vue: typeof Vue, _components: Record<string,typeof Vue>) {
    let components = app().hooks.installComponents.call({ ..._components })
    Object.keys(components).forEach(key => {
        let componentName = key;
        if ( app().config.prefix ) {
            componentName = `${app().config.prefix}-${kebabCase(key)}`;
        }
        log('prefixAndRegisterComponents componentName', componentName)
        _Vue.component(componentName, components[ key ])
    });
    return components;
}


export function registerElementComponents(_Vue: typeof Vue, _components:  Record<string,typeof Vue>) {
    let components = app().hooks.installComponents.call({ ..._components })

    Object.values(components).forEach(component => {
        log('registerElementComponents component.name', component.name)

        _Vue.component(component.name, component)
    })

    return components;
}
