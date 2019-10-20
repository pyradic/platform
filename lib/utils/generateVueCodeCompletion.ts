import Vue, { PropOptions } from 'vue';
import { mergeChildPrototypes } from './mergeChildPrototypes';
import { camelCase, capitalize, kebabCase } from 'lodash';
import ElementUi from 'element-ui'

Vue.use(ElementUi)

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

export function generateVueCodeCompletion() {
    // let _plugin    = getPlugin()
    let components = mergeChildPrototypes(Vue.options.components)
    // let plugins    = getPlugins().filter(plugin => plugin.NAME !== _plugin.NAME)
    // for ( let plugin of plugins ) {
    //     components.push(...getComponentsFromPlugin(plugin));
    // }

    log({ components })
    let generated = []
    Object.keys(components).forEach(key => {
        let c    = components[ key ];
        let name = kebabCase(key);

        if ( key.startsWith('El') ) {
            name=key
        } else if ( key.startsWith('el-') ) {
            name = capitalize(camelCase(key))
        }
        if ( c[ 'options' ] && c[ 'options' ][ 'props' ] ) {
            generated.push({
                name,
                props: transformPropsData(c[ 'options' ][ 'props' ] as any)
            })
        } else if ( c[ 'props' ] ) {
            generated.push({
                name,
                props: transformPropsData(c[ 'props' ] as any)
            })
        }
        log('generated', key, { name, c })
    })
    // p.components[1].options.props.value.type.name.toString()

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
    return { components, lines, result, getResult };
}
