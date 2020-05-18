import { strEnsureLeft } from '@u/general';

export class Css3 {

    static set(propertyName: string, value: string | null, priority?: string | null) {
        propertyName = strEnsureLeft(propertyName, '--');
        window.getComputedStyle(window.document.documentElement).setProperty(propertyName, value, priority);
        // window.document.documentElement.style.setProperty(propertyName, value, priority);
    }

    static get(propertyName: string) {
        propertyName = strEnsureLeft(propertyName, '--');
        return window.getComputedStyle(window.document.documentElement).getPropertyValue(propertyName)
    }

    static remove(propertyName: string) {
        propertyName = strEnsureLeft(propertyName, '--');
        window.getComputedStyle(window.document.documentElement).removeProperty(propertyName);
        // window.document.documentElement.style.removeProperty(propertyName);
    }
}