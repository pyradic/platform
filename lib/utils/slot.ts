import Vue from 'vue';

export function slot(comp: Vue, name: string, otherwise: any = null) {
    if ( comp.$slots[ name ] ) {
        return comp.$slots[ name ]
    }
    return otherwise
}
export const when = (condition, then) => condition ? then : null
