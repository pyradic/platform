import { kebabCase } from 'lodash';
import Vue, { VueConstructor } from 'vue';
import { app } from '@c/Application';


const log = require('debug')('utils:registerComponents');

export function prefixAndRegisterComponents(_Vue: VueConstructor, _components: Record<string, any>, extraPrefix:string=null, prefix:string=app.config.prefix) {
    let components = app.hooks.installComponents.call({ ..._components })
    Object.keys(components).forEach(key => {
        let componentName = key;
        if ( prefix ) {
            componentName = `${prefix}-`;
            if(extraPrefix){
                componentName += `${extraPrefix}-`
            }
            componentName += kebabCase(key);
        }
        log('prefixAndRegisterComponents componentName', componentName, {key:components[ key ]})
        _Vue.component(componentName, components[ key ])
    });
    return components;
}


export function registerElementComponents(_Vue: typeof Vue, _components: Record<string, any>) {
    let components = app.hooks.installComponents.call({ ..._components })

    Object.values(components).forEach((component:any) => {
        log('registerElementComponents component.name', component.name)

        _Vue.component(component.name, component)
    })

    return components;
}
