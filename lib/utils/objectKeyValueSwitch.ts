export const objectKeyValueSwitch = <A extends any,B extends any>(obj:Record<A,B>):Record<B,A> =>
    Object.assign({}, ...Object.entries(obj as any as Record<string,string>)
        .map(([ a, b ]) => ({ [ b ]: a })));