import Vue, { PropOptions } from 'vue';
import { mergeChildPrototypes } from './mergeChildPrototypes';
import { camelCase, capitalize, kebabCase } from 'lodash';
import { Component } from 'vue/types/options';
const log = require('debug')('utils:generatevuecode');

function getPlugin() {
    let plugins = getPlugins()
    for ( let plugin of plugins ) {
        if ( plugin.NAME && plugin.NAME === '@crvs/admin' ) {
            return plugin;
        }
    }
    console.dir({ plugins })
    throw new Error('not found plugin')
}

function getPlugins() {
    return Vue[ '_installedPlugins' ] as any[]
}

function getComponentsFromPlugin(plugin): Array<typeof Vue> {

    let components: Array<typeof Vue> = []
    Object.keys(plugin).forEach(key => {
        let val = plugin[ key ]
        if ( val && val.name && val.name === 'VueComponent' ) {
            components.push(val)
        }
    })
    return components
}

// type Props = Record<string, PropOptions<any>>;
//
// interface Transformer {
//     id:string
//     transform(data: { name: string, props: Props, component: Component }): string
//
//     finalize(transformed: string[]): string
// }
//
// export class CompletionGenerator {
//     transformers: Transformer[]
//
//     register(transformer: Transformer) {
//         this.transformers.push(transformer)
//     }
//
//     generate() {
//         for ( const transformer of this.transformers ) {
//             for ( const component of this.getComponents() ) {
//                 transformer.transform(component)
//             }
//         }
//     }
//
//     getComponents(): Array<{ name: string, props: Props, component: Component }> {
//         let components     = mergeChildPrototypes(Vue.options.components)
//         let componentNames = Object.keys(components);
//
//         return componentNames.map(name => {
//             let component = components[ name ];
//             let props     = component?.options?.props ?? component.props
//             return { name, component, props }
//         });
//     }
// }
//
// export class VueCompletionTransformer implements Transformer {
//     id = 'vue'
//     public transform(data: { name: string; props: Props; component: Component }): string {
//
//     }
//
//     public finalize(transformed: string[]): string {
//
//     }
// }

function transformPropsData(props: Record<string, PropOptions<any>>) {
    let propNames = Object.keys(props);
    for ( let name of propNames ) {
        let prop = props[ name ];
    }
    return propNames.map(name => {
        let prop = props[ name ];
        // log('transformPropsData', name, { propNames, prop });

        let type = ``;
        if ( prop.type && prop.type[ 'name' ] ) {
            type = `type: ${prop.type[ 'name' ]},`
        }

        let def = ``
        if ( prop.default ) {
            def = `default: ${JSON.stringify(prop.default)},`
        }

        let out = `
${name}: {
    ${def}
    ${type}
}`;

        return out;
    })
    // props.asdf.type.name.string()
}

function transformPropsDataJsx(props: Record<string, PropOptions<any>>) {
    let propNames = Object.keys(props);
    for ( let name of propNames ) {
        let prop = props[ name ];
    }
    return propNames.map(name => {
        let prop = props[ name ];
        // log('transformPropsData', name, { propNames, prop });

        let type = `any`;
        if ( prop.type && prop.type[ 'name' ] ) {
            type = prop.type[ 'name' ].toLowerCase();
        }
        if(type === 'function'){
            type = 'Function'
        }
        if(type === 'array'){
            type = 'Array<any>'
        }

        name = camelCase(name)

        return `'${name}'?: ${type}`;
    })
    // props.asdf.type.name.string()
}

export function generateVueCodeCompletion() {
    // let _plugin    = getPlugin()
    let components = mergeChildPrototypes(Vue.options.components)
    // let plugins    = getPlugins().filter(plugin => plugin.NAME !== _plugin.NAME)
    // for ( let plugin of plugins ) {
    //     components.push(...getComponentsFromPlugin(plugin));
    // }

    log({ components })
    let generated = []
    let jsx = []
    Object.keys(components).forEach(key => {
        let c    = components[ key ];
        let name = kebabCase(key);

        if ( key.startsWith('El') ) {
            name = key
        } else if ( key.startsWith('el-') ) {
            name = capitalize(camelCase(key))
        }
        // if ( c[ 'options' ] && c[ 'options' ][ 'props' ] ) {
        //     generated.push({
        //         name,
        //         props: transformPropsData(c[ 'options' ][ 'props' ] as any)
        //     })
        // } else if ( c[ 'props' ] ) {
        //     generated.push({
        //         name,
        //         props: transformPropsData(c[ 'props' ] as any)
        //     })
        // }
        generated.push({ name, props: transformPropsData(c?.options?.props ?? (c.props || {})) })
        jsx.push({name: kebabCase(key), props: transformPropsDataJsx(c?.options?.props ?? (c.props || {}))})
        log('generated', key, { name, c })
    })
    // p.components[1].options.props.value.type.name.toString()

    let jsxLines = jsx.map(gen => `"${gen.name}"?: TsxComponentAttrs<{ \n${gen.props.join('\n')}\n }>`)

    let lines = generated.map(gen => {
        let prop = gen.props.join(',')
        return `Vue.component('${gen.name}', {
    props: {
        ${prop}
    }
});\n`
    })

    lines.unshift('import Vue from "vue";')
    let result    = lines.join('\n');
    let getResult = () => result.replace('↵', '\n');
    console.log(result)

    let jsxResult = jsxLines.join("\n")
    let getJsxResult = () => jsxResult.replace('↵', '\n');
    return { components, lines, result, getResult, jsxResult, getJsxResult };
}
