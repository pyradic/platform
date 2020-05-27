import { merge } from '@/index';

export const createKeyListener = (code: string, callback: Function, modifiers: { shift?: boolean, alt?: boolean, ctrl?: boolean, } = {}) => {
    modifiers      = merge({ shift: false, alt: false, ctrl: false }, modifiers);
    const listener = (event: KeyboardEvent) => {
        if ( event.code === code && Object.entries(modifiers).filter(([ key, val ]) => event[ key + 'Key' ] !== val).length === 0 ) {
            callback(event);
        }
    };
    return {
        bind  : () => window.addEventListener('keyup', listener),
        unbind: () => window.removeEventListener('keyup', listener),
    };

};
