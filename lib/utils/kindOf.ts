type KindsOf='number'|'string'|'boolean'|'function'|'regexp'|'array'|'date'|'error'|'object'
let kindsOf = {};
'Number String Boolean Function RegExp Array Date Error'.split(' ').forEach(function (k) {
    kindsOf['[object ' + k + ']'] = k.toLowerCase();
});
let nativeTrim = String.prototype.trim;
let entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    '\'': '&#39;',
    '/': '&#x2F;'
};
export function kindOf(value:any):KindsOf {
    // Null or undefined.
    if (value == null) {
        return String(value) as any;
    }
    // Everything else.
    return kindsOf[kindsOf.toString.call(value)] || 'object';
}