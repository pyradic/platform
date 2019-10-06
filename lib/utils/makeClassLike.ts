
export function makeClassLikeNumber(obj) {
    let prototype = Object.create(Number.prototype, { constructor: { value: obj } })
    Object.setPrototypeOf(obj, prototype);
    obj.prototype.valueOf = function () {
        return this.value;
    };
    [ 'toExponential', 'toFixed', 'toLocaleString', 'toPrecision', 'toString' ].forEach(function (name) {
        obj.prototype[ name ] = function () {
            // return Number.prototype[ name ].apply(new Number(this.value), arguments);
            return Number.prototype[ name ].apply(Number(this.value), arguments);
        };
    });
}

export function makeClassLikeString(obj) {
    let prototype = Object.create(String.prototype, { constructor: { value: obj } })
    Object.setPrototypeOf(obj, prototype);
    obj.prototype.valueOf = function () {
        return this.value;
    };
    [ 'anchor', 'big', 'blink', 'bold', 'charAt', 'charCodeAt', 'codePointAt', 'concat',
        'endsWith', 'fontcolor', 'fontsize', 'fixed', 'includes', 'indexOf', 'italics',
        'lastIndexOf', 'link', 'localeCompare', 'match', 'normalize', 'padEnd', 'padStart',
        'repeat', 'replace', 'search', 'slice', 'small', 'split', 'strike', 'sub', 'substr',
        'substring', 'sup', 'startsWith', 'toString', 'trim', 'trimLeft', 'trimRight', 'toLowerCase',
        'toUpperCase', 'valueOf', 'toLocaleLowerCase', 'toLocaleUpperCase', 'trimStart', 'trimEnd',
        'toString' ].forEach(function (name) {
        obj.prototype[ name ] = function () {
            return String.prototype[ name ].apply(String(this.value), arguments);
        };
    });
}
