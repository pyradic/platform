import _debug from 'debug'

const namespaces:Record<string, _debug.Debugger> = {}

export function debug(namespace){
    if(namespaces[namespace] === undefined) {
        namespaces[ namespace ] = _debug(namespace);
    }
    return namespaces[ namespace ];
}
