import { Styles } from '@/interfaces';

export function styleToString(style: Styles) {
    var elm = new Option;
    Object.keys(style).forEach(function (a) {
        elm.style[ a ] = style[ a ];
    });
    return elm.getAttribute('style');
}
