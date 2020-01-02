import { Builder }       from '@pyro/webpack/lib';
import { join, resolve } from "path";


export function configure(builder: Builder) {
    const {wp,options,addons,env} = builder

    wp.resolve.alias.merge({
        '@c'                                 : join(__dirname, 'lib/classes/'),
        '@u'                                 : join(__dirname, 'lib/utils/'),
        '@'                                  : join(__dirname, 'lib/'),
        '#'                                  : join(__dirname, 'lib/components/')
    });
    builder.hooks.initialized.tap('@pyro/menus-module', builder => {
        const { wp } = builder

        // wp.module.rule('babel').exclude.add(/el-menu/)
        // wp.module.rule('el-menu').test(/\.(js|mjs|jsx)$/).include.add(/el-menu/);
        // wp.blocks.rules.babel(wp, {
        //     'presets': [ [ '@babel/env', { 'loose': true, 'modules': false, 'targets': { 'browsers': [ '> 1%', 'last 2 versions', 'not ie <= 8' ] } } ] ],
        //     'plugins': [ 'transform-vue-jsx' ],
        //     'env'    : {
        //         'utils': {
        //             'presets': [ [ '@babel/env', { 'loose': true, 'modules': 'commonjs', 'targets': { 'browsers': [ '> 1%', 'last 2 versions', 'not ie <= 8' ] } } ] ],
        //             'plugins': [ [ 'module-resolver', { 'root': [ 'element-ui' ], 'alias': { 'element-ui/src': 'element-ui/lib' } } ] ]
        //         }
        //     }
        // }, 'el-menu')
    })
}