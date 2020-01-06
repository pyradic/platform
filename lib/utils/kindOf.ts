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


export const isNumber   = (value: any):value is number => kindOf(value) === 'number';
export const isString   = (value: any):value is string => kindOf(value) === 'string';
export const isBoolean  = (value: any):value is boolean => kindOf(value) === 'boolean';
export const isFunction = (value: any):value is Function => kindOf(value) === 'function';
export const isRegExp   = (value: any):value is RegExp => kindOf(value) === 'regexp';
export const isArray    = (value: any):value is Array<any> => kindOf(value) === 'array';
export const isDate     = (value: any):value is Date => kindOf(value) === 'date';
export const isError    = (value: any):value is Error => kindOf(value) === 'error';
export const isObject    = (value: any):value is object => kindOf(value) === 'object';
