import EventEmitter, { ConstructorOptions, EventEmitter2 } from 'eventemitter2';

export function bindEventEmitter(
    emitter: EventEmitter2,
    obj: any,
    methods: string[] = [ 'emit', 'emitAsync', 'addListener', 'on', 'prependListener', 'once', 'prependOnceListener', 'many', 'prependMany', 'onAny', 'prependAny', 'offAny', 'removeListener', 'off', 'removeAllListeners', 'setMaxListeners', 'eventNames', 'listeners', 'listenersAny' ],
) {
    methods.forEach(name => {
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
