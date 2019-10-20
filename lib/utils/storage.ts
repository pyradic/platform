import { merge } from 'lodash'
import lzs from 'lz-string'
import { injectable } from 'inversify';
import cook from 'js-cookie';

const typePrefix = '__c_';

function compress(value) {
    return typePrefix + 'lz-s|' + lzs.compressToUTF16(value)
}

function decompress(value) {
    let type, length, source

    length = value.length
    if ( length < 9 ) {
        // then it wasn't compressed by us
        return value
    }

    type   = value.substr(0, 8)
    source = value.substring(9)

    if ( type === typePrefix + 'lz-s' ) {
        value = lzs.decompressFromUTF16(source)
    }

    return value;
}

function encode(value) {
    if ( Object.prototype.toString.call(value) === '[object Date]' ) {
        return typePrefix + 'date|' + value.toUTCString()
    }
    if ( Object.prototype.toString.call(value) === '[object RegExp]' ) {
        return typePrefix + 'expr|' + value.source
    }
    if ( typeof value === 'number' ) {
        return typePrefix + 'numb|' + value
    }
    if ( typeof value === 'boolean' ) {
        return typePrefix + 'bool|' + (value ? '1' : '0')
    }
    if ( typeof value === 'string' ) {
        return typePrefix + 'strn|' + value
    }
    if ( typeof value === 'function' ) {
        return typePrefix + 'strn|' + value.toString()
    }
    if ( value === Object(value) ) {
        return typePrefix + 'objt|' + JSON.stringify(value)
    }

    // hmm, we don't know what to do with it,
    // so just return it as is
    return value
}

function decode(value) {
    let type, length, source

    length = value.length
    if ( length < 9 ) {
        // then it wasn't encoded by us
        return value
    }

    type   = value.substr(0, 8)
    source = value.substring(9)

    switch ( type ) {
        case typePrefix + 'date':
            return new Date(source)

        case typePrefix + 'expr':
            return new RegExp(source)

        case typePrefix + 'numb':
            return Number(source)

        case typePrefix + 'bool':
            return Boolean(source === '1')

        case typePrefix + 'strn':
            return '' + source

        case typePrefix + 'objt':
            return JSON.parse(source)

        default:
            // hmm, we reached here, we don't know the type,
            // then it means it wasn't encoded by us, so just
            // return whatever value it is
            return value
    }
}

export interface StorageDriverInterface {
    name: string

    get<T extends any = any>(key: string): T

    set(key: string, value: any)

    has(key: string): boolean

    unset(key: string)

    clear()

    getSize(): number
}

export interface ParsedStorageOptions {
    encode?: (value) => any
    decode?: (value) => any
    compress?: (value) => any
    decompress?: (value) => any
    driver?: StorageDriverInterface
    options?: StorageOptions
}

export interface StorageOptions {
    compression?: boolean
    seralization?: boolean
    driver?: string
}

export interface StorageConstructorOptions extends StorageOptions {
    drivers?: StorageDriverInterface[],
}

@injectable()
export class Storage {
    protected drivers: Record<string, StorageDriverInterface> = {}
    protected driver: StorageDriverInterface
    public readonly handlers                                  = {
        serialize  : value => encode(value),
        deserialize: value => decode(value),
        compress   : value => compress(value),
        decompress : value => decompress(value)
    }
    public readonly options: StorageOptions                   = {
        compression : false,
        seralization: true,
        driver      : null
    }

    configure(options: StorageConstructorOptions = {}) {
        options = merge({ drivers: Storage.defaultDrivers(), driver: 'local' }, options)
        for ( let driver of options.drivers ) {
            this.registerDriver(driver)
        }
        this.use(options.driver);
        return this;
    }

    protected o(o: StorageOptions = {}): ParsedStorageOptions {
        let options = merge({}, this.options, o)
        return {
            decode    : value => options.seralization ? this.handlers.deserialize(value) : value,
            encode    : value => options.seralization ? this.handlers.serialize(value) : value,
            decompress: value => options.compression ? this.handlers.decompress(value) : value,
            compress  : value => options.compression ? this.handlers.compress(value) : value,
            driver    : options.driver in this.drivers ? this.drivers[ options.driver ] : this.driver,
            options   : options
        }
    }

    public registerDriver(driver: StorageDriverInterface) {
        this.drivers[ driver.name ] = driver;
        if ( !this.driver ) {
            this.driver = driver;
        }
        return this;
    }

    public use(driverName: string) {
        if ( driverName && driverName in this.drivers ) {
            this.driver = this.drivers[ driverName ];
        }
        return this;
    }

    public has(key: string, options?: StorageOptions) {
        return this.o(options).driver.has(key);
    }

    public get<T>(key: string, defaultValue?: any, options?: StorageOptions): T {
        let { driver, decode, decompress } = this.o(options);
        let value                          = defaultValue;
        if ( driver.has(key) ) {
            value = driver.get(key);
            value = decompress(value)
            value = decode(value)
        }
        return value;
    }

    public set(key: string, value: any, options?: StorageOptions) {
        let { driver, encode, compress } = this.o(options);
        value                            = encode(value)
        value                            = compress(value)
        driver.set(key, value);
        return this;
    }

    public unset(key: string, value: any, options?: StorageOptions) {
        this.o(options).driver.unset(key);
        return this;
    }

    public clear(options?: StorageOptions) {
        this.o(options).driver.clear();
        return this;
    }

    public static defaultDrivers(): StorageDriverInterface[] {
        return [
            new StorageDriver('local', window.localStorage),
            new StorageDriver('session', window.sessionStorage)
        ]
    }
}

class StorageDriver implements StorageDriverInterface {
    constructor(public name: string, protected storage: WindowLocalStorage['localStorage']) {}

    public get<T>(key: string): T { return this.storage.getItem(key) as any }

    public has(key: string) { return this.storage.getItem(key) !== undefined && this.storage.getItem(key) !== null }

    public set(key: string, value: any) { this.storage.setItem(key, value); }

    public unset(key: string) { this.storage.removeItem(key) }

    public clear() { this.storage.clear() }

    public getSize() { return this.storage.length; }
}

export interface CookieOptions {
    compression?: boolean
    seralization?: boolean
}


@injectable()
export class Cookies {
    get defaults(): cook.CookieAttributes {return cook.defaults}

    set defaults(defaults: cook.CookieAttributes) {cook.defaults = defaults}

    constructor(){
        this.defaults.expires = 30; // days
    }
    get(name, defaultValue?: any) {
        if ( !this.has(name) ) {
            return defaultValue
        }
        return cook.get(name)
    }

    has(name) {return cook.get(name) !== undefined }

    set(name: string, value: string | object, options: cook.CookieAttributes = {}) {
        cook.set(name, value, options)
        return this;
    }

    unset(name: string, options: cook.CookieAttributes = {}) {
        cook.remove(name, options)
        return this;
    }
}


// let stor = window[ 'stor' ] = new Storage({
//     driver : 'local',
//     drivers: Storage.defaultDrivers()
// })

// stor.clear()
// stor.options.compression = true;
// stor.set('var.foo', { a: 'b', bv: 34, c: true, d: { pp: 'cc' } })
//
//
// export let lzstest = window[ 'lzstest' ] = {
//     basic() {
//         var string = 'This is my compression test.';
//         console.log('Size of sample is: ' + string.length);
//         var compressed = lzs.compress(string);
//         console.log('Size of compressed sample is: ' + compressed.length);
//         localStorage.setItem('lzstest-basic', compressed);
//         string = lzs.decompress(localStorage.getItem('lzstest-basic'));
//         console.log('Sample is: ' + string);
//     },
//     utf16() {
//         var string = 'This is my compression test.';
//         console.log('Size of sample is: ' + string.length);
//         var compressed = lzs.compressToUTF16(string);
//         console.log('Size of compressed sample is: ' + compressed.length);
//         localStorage.setItem('lzstest-utf16', compressed);
//         string = lzs.decompressFromUTF16(localStorage.getItem('myData'));
//         console.log('Sample is: ' + string);
//     },
//     uint() {
//         var string = 'This is my compression test.';
//         console.log('Size of sample is: ' + string.length);
//         var compressed = lzs.compressToUint8Array(string);
//         console.log('Size of compressed sample is: ' + compressed.length);
//         // localStorage.setItem("lzstest-utf16",compressed.join(''));
//         string = lzs.decompressFromUint8Array(compressed);
//         console.log('Sample is: ' + string);
//     }
// }

