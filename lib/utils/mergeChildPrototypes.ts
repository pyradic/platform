export function mergeChildPrototypes(val:any){
    let value=val;
    let result = value ? {...value} : {}
    while(value !== null){
        result = {...result, ...value}
        value = Object.getPrototypeOf(value)
    }
    return result;
}