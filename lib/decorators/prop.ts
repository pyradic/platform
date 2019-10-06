import 'reflect-metadata';
import Vue, { PropOptions } from 'vue';
import { createDecorator } from 'vue-class-component';
import { kindOf } from '@u/kindOf';
import { app } from '@c/Application';

export interface TargetedPropOptions extends PropOptions {
    target?: Vue
}

export type PropRequiredDecorator = {
    (): PropertyDecorator
    (validator?: PropOptions['validator']): PropertyDecorator
}
export type PropTypeDecorator = {
    (): PropertyDecorator
    (defaultValue?: PropOptions['default'], validator?: PropOptions['validator']): PropertyDecorator
} & {
    required: PropRequiredDecorator
}
export type PropDecoratorTypes = 'boolean' | 'string' | 'number' | 'array' | 'object' | 'function'
export type PropTypeDecorators = {
    [K in PropDecoratorTypes]: PropTypeDecorator
}

export type PropDecorator = {
    (options: PropOptions): PropertyDecorator
    (type: PropOptions['type'], defaultValue?: PropOptions['default'], required?: PropOptions['required'], validator?: PropOptions['validator']): PropertyDecorator
    sync(propName: string, options: PropOptions): PropertyDecorator
    sync(propName: string, type: PropOptions['type'], defaultValue?: PropOptions['default'], required?: PropOptions['required'], validator?: PropOptions['validator']): PropertyDecorator
    classPrefix(prefix: string): PropertyDecorator
} & PropTypeDecorators

const reflectMetadataIsSupported = typeof Reflect !== 'undefined' && typeof Reflect.getMetadata !== 'undefined';

function applyMetadata(options: PropOptions, target: Vue, key: string | symbol) {
    if ( reflectMetadataIsSupported && typeof options.type === 'undefined' ) {
        options.type = Reflect.getMetadata('design:type', target, key);
    }
}

function applyPropDecorator(options: PropOptions, target: Vue, key: string | symbol) {
    applyMetadata(options, target, key);
    createDecorator((componentOptions, k) => {
        (componentOptions.props || ((componentOptions.props = {}) as any))[ k ] = options;
    })(target, key.toString());
}

function createPropDecorator(options: TargetedPropOptions, cb?: (options: TargetedPropOptions) => TargetedPropOptions): PropertyDecorator {
    return (target: Vue, key) => {
        options.target = target
        if ( cb ) {
            options = cb(options);
        }
        return applyPropDecorator(options, target, key);
    }
}

let propTypeMap = {
    boolean : Boolean,
    string  : String,
    number  : Number,
    array   : Array,
    object  : Object,
    function: Function,
};

function getPropOptions(params: any[]): PropOptions {
    let options: PropOptions = {};
    if ( typeof params[ 0 ] === 'object' ) {
        options = params[ 0 ];
    } else if ( typeof params[ 0 ] === 'function' ) {
        options.type      = params[ 0 ];
        options.default   = params[ 2 ] === true ? undefined : params[ 1 ];
        options.required  = params[ 2 ] === true;
        options.validator = params[ 3 ];
    }
    return options;
}

export let prop: PropDecorator;
prop             = function (...params): PropertyDecorator {
    let options = getPropOptions(params)
    return createPropDecorator(options);
} as any;
prop.sync        = function (propName: string, ...params): PropertyDecorator {
    let options = getPropOptions(params)
    return (target: Vue, key: string) => {
        applyMetadata(options, target, key);
        createDecorator((componentOptions, k) => {
            (componentOptions.props || (componentOptions.props = {} as any))[ propName ] = options;
            (componentOptions.computed || (componentOptions.computed = {}))[ k ]         = {
                get() {
                    return (this as any)[ propName ];
                },
                set(value) {
                    // @ts-ignore
                    this.$emit(`update:${propName}`, value);
                },
            };
        })(target, key);
    };
};
prop.classPrefix = function (defaultName: string): PropertyDecorator {
    let options: PropOptions = {
        type   : String,
        default: () => {
            if ( app().config.prefix ) {
                return app().config.prefix + '-' + defaultName
            }
            return defaultName
        },
    }
    return createPropDecorator(options)
}

Object.keys(propTypeMap).forEach(key => {
    prop[ key ]          = function (...params) {
        let options: PropOptions = {}

        if ( kindOf(params[ 0 ]) === 'object' ) {
            options = params[ 0 ];
        } else {
            options = {
                type     : propTypeMap[ key ],
                default  : params[ 0 ],
                validator: params[ 1 ],
            }
        }
        return createPropDecorator(options);
        // return createPropDecorator(options, options => {
        //     if ( options.default ) {
        //         if ( kindOf(options.default) === 'array' ) {
        //             let def         = options.default
        //             options.default = (target) => def
        //         }
        //         if ( kindOf(options.default) === 'function' ) {
        //             let def: Function = options.default
        //             options.default   = def.bind(options.target, options.target)
        //         }
        //     }
        //     return options
        // });
    };
    prop[ key ].required = function (...params) {
        let options: PropOptions = {
            type     : propTypeMap[ key ],
            required : true,
            validator: params[ 0 ],
        };
        return createPropDecorator(options);
    };
});

/*
class Test {

    @prop({ type: String, required: true }) first: string;
    @prop({ type: String, default: '' }) firstDeafult: string;
    @prop(String) second: string | null;
    @prop(String, '') third: string;
    @prop(String, null, true) fourth: string;

    @prop.string() string: string;
    @prop.string('') stringWithDefault: string;
    @prop.string.required() stringRequired: string;
    @prop.string.required((value) => value === 'a') stringRequiredWithValidator: string;

    @prop.sync('sdf', Boolean) reqArray:any[];
}

new Test;
*/
