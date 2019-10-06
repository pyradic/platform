
const OBSERVER = Symbol.for('observable')

export type ObserverChangedType = 'update' | 'add' | 'delete';

export interface ObserverChanged<O = any, K extends keyof O = keyof O, V = O[K]> {
    object: O
    type: ObserverChangedType
    name: K;
    newValue?: V;
    oldValue?: V;
}

export type ObserverChangedFunction<O = any, K extends keyof O = keyof O, V = O[K]> = (change: ObserverChanged<O, K, V>) => void

export type ObserverSubscription = { unsubscribe() }

export class Observable<T extends object> {
    public readonly subscribers: ObserverChangedFunction[] = [];
    public readonly proxy: T;

    constructor(public readonly object: T) {
        this.proxy = this.applyProxy(object)
        const self = this;
        Object.defineProperty(object, OBSERVER, {
            get(): any {
                return self
            },
        })
    }

    subscribe(change: ObserverChangedFunction): ObserverSubscription {
        let subcriptionIndex = this.subscribers.push(change)
        return {
            unsubscribe: () => this.subscribers.splice(subcriptionIndex + 1, 1),
        }
    }

    createChanged(type: ObserverChangedType, object, name, newValue?: any, oldValue?: any): ObserverChanged {
        return { type, object, name, newValue, oldValue }
    }

    callSubscribers(changed: ObserverChanged) {
        this.subscribers.forEach(subscriber => {
            subscriber(changed); //.call(subscriber, 1)
        })
    }

    applyProxy<O extends object>(object: O): O {
        const self  = this;
        const proxy = new Proxy<O>(object, {
            get(target: any, p: string | number | symbol, receiver: any): any {
                let value = Reflect.get(target, p, receiver);
                if ( typeof value === 'object' ) {
                    value = self.applyProxy(value)
                }
                return value
            },
            set(target: any, p: string | number | symbol, value: any, receiver: any): boolean {
                let has      = Reflect.has(target, p)
                let oldValue = Reflect.get(target, p, receiver)
                let res      = Reflect.set(target, p, value, receiver)
                if ( res ) {
                    let type: ObserverChangedType = has ? 'update' : 'add'
                    self.callSubscribers(self.createChanged(type, target, p, value, oldValue));
                }
                return res;
            },
            deleteProperty(target: any, p: string | number | symbol): boolean {
                let res = Reflect.deleteProperty(target, p)
                if ( res ) {
                    self.callSubscribers(self.createChanged('delete', target, p));
                }
                return res;
            },
            has(target: O, p: string | number | symbol): boolean {
                return Reflect.deleteProperty(target, p)
            },
        })
        return proxy;
    }
}

export function observable<T extends object>(object: T): T & { [ OBSERVER ]: Observable<T> } {
    let observable       = new Observable<T>(object);
    let observableObject = observable.proxy
    return observable.proxy as any
}

export function observe<T extends object>(object: T & { [ OBSERVER ]?: Observable<T> }, changeCallback: ObserverChangedFunction) {
    if ( object[ OBSERVER ] ) {

    }
    return object[ OBSERVER ].subscribe(changeCallback)
}
