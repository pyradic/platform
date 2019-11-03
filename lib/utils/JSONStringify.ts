var hasProp = Object.prototype.hasOwnProperty;

function throwsMessage(err) {
    return '[Throws: ' + (err ? err.message : '?') + ']';
}

function safeGetValueFromPropertyOnObject(obj, property) {
    if ( hasProp.call(obj, property) ) {
        try {
            return obj[ property ];
        } catch ( err ) {
            return throwsMessage(err);
        }
    }

    return obj[ property ];
}

function ensureProperties(obj) {
    var seen = []; // store references to objects we have seen before

    function visit(obj) {
        if ( obj === null || typeof obj !== 'object' ) {
            return obj;
        }

        if ( seen.indexOf(obj) !== - 1 ) {
            return '[Circular]';
        }
        seen.push(obj);


        try {
            if (
                'toJSON' in obj
                && typeof obj.toJSON === 'function'
            ) {
                try {
                    var fResult = visit(obj.toJSON());
                    seen.pop();
                    return fResult;
                } catch ( err ) {
                    return throwsMessage(err);
                }
            }
        } catch ( err ) {
            return throwsMessage(err);
        }
        if ( Array.isArray(obj) ) {
            var aResult = obj.map(visit);
            seen.pop();
            return aResult;
        }

        var result = Object.keys(obj).reduce(function (result, prop) {
            // prevent faulty defined getter properties
            result[ prop ] = visit(safeGetValueFromPropertyOnObject(obj, prop));
            return result;
        }, {});
        seen.pop();
        return result;
    };

    return visit(obj);
}


export function JSONstringify(obj, replacer?, spaces?) {
    return JSON.stringify(ensureProperties(obj), replacer, spaces);
}
