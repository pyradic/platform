import EventEmitter, { ConstructorOptions, EventEmitter2 } from 'eventemitter2';

export type BindEventEmitterMethod = 'emit' | 'emitAsync' | 'addListener' | 'on' | 'prependListener' | 'once' | 'prependOnceListener' | 'many' | 'prependMany' | 'onAny' | 'prependAny' | 'offAny' | 'removeListener' | 'off' | 'removeAllListeners' | 'setMaxListeners' | 'eventNames' | 'listeners' | 'listenersAny'

export interface BindEventEmitterOptions {
    methods?: Array<BindEventEmitterMethod>
    except?: Array<BindEventEmitterMethod>
    only?: Array<BindEventEmitterMethod>
}

export function bindEventEmitter(
    emitter: EventEmitter2,
    obj: any,
    options: BindEventEmitterOptions = {},
) {
    options = {
        methods: [ 'emit', 'emitAsync', 'addListener', 'on', 'prependListener', 'once', 'prependOnceListener', 'many', 'prependMany', 'onAny', 'prependAny', 'offAny', 'removeListener', 'off', 'removeAllListeners', 'setMaxListeners', 'eventNames', 'listeners', 'listenersAny' ],
        except : [],
        ...options,
    };

    options.methods
        .filter(name => !options.except.includes(name))
        .forEach(name => {
            obj[ name ] = emitter[ name ].bind(emitter);
        });
}


export function createEventEmitter(options: ConstructorOptions = {}): EventEmitter2 {
    return new (EventEmitter as any)({
        delimiter   : ':',
        maxListeners: Infinity,
        newListener : true,
        wildcard    : true,
        ...options,
    }) as any;
}

export type BoundEmitter = Omit<EventEmitter2, 'constructor'>
