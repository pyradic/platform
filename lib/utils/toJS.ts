import { JSONstringify } from '@u/JSONStringify';

export function toJS(obj){
    return JSON.parse(JSONstringify(obj));
}
