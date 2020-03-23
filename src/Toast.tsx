class BEM {
    static currentBlock;
    static currentElement;
    static statePrefix = 'is-'; // or 'has-'

    static B(name, modifiers = [], states = []) {
        this.currentBlock = name;
        this.currentElement = null;
        return [ this.currentBlock, this.M(modifiers), this.S(states) ].join(' ');
    }

    static E(name, modifiers = [], states = []) {
        this.currentElement = name;
        return [ `${this.currentBlock}__${this.currentElement}`, this.M(modifiers), this.S(states) ].join(' ');
    }

    static M(name) {
        if ( Array.isArray(name) ) {
            return name.map(this.M).join(' ');
        }
        return this.currentElement ?
               `${this.currentBlock}__${this.currentElement}--${name}` :
               `${this.currentBlock}--${name}`;
    }

    static S(name) {
        return Array.isArray(name) ? name.map(this.S).join(' ') : this.statePrefix + name;
    }
}

const { B, E, M, S } = BEM;


export function Toast(props) {
    return (
        <div className={B('Toast', [ props.category ])}>
            <main className={E('message')}>
                <header className={E('category')}>
                    {props.category}
                </header>
                <p className={E('text')}>{props.message}</p>
            </main>
            <button
                className={E('button', [ 'primary' ], [ 'disabled' ])} // Toast__button Toast__button--primary is-disabled
                className={E('button') + ' ' + M('primary') + ' ' + S('disabled')} // Toast__button Toast__button--primary is-disabled
                className={classNames(
                    E('button'),
                    M(props.type),
                    {
                        [ M('primary') ]: props.primary,
                        [ M('info') ]   : props.info,
                        [ M('danger') ] : props.danger,
                    },
                    {
                        [ S('disabled') ]: props.disabled,
                    },
                )}
                type="button"
                onClick={props.dismiss}
            >
                Dismiss
            </button>
        </div>
    );
}