export type KindsOf = 'number' | 'string' | 'boolean' | 'function' | 'regexp' | 'array' | 'date' | 'error' | 'object'
let kindsOf = {};
'Number String Boolean Function RegExp Array Date Error'.split(' ').forEach(function (k) {
    kindsOf[ '[object ' + k + ']' ] = k.toLowerCase();
});

export function kindOf(value: any): KindsOf {
    // Null or undefined.
    if ( value == null ) {
        return String(value) as any;
    }
    // Everything else.
    return kindsOf[ kindsOf.toString.call(value) ] || 'object';
}


export const isNumber   = (value: any) => kindOf(value) === 'number';
export const isString   = (value: any) => kindOf(value) === 'string';
export const isBoolean  = (value: any) => kindOf(value) === 'boolean';
export const isFunction = (value: any) => kindOf(value) === 'function';
export const isRegExp   = (value: any) => kindOf(value) === 'regexp';
export const isArray    = (value: any) => kindOf(value) === 'array';
export const isDate     = (value: any) => kindOf(value) === 'date';
export const isError    = (value: any) => kindOf(value) === 'error';
export const isObject    = (value: any) => kindOf(value) === 'object';