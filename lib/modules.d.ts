// noinspection ES6UnusedImports
import Cash from 'cash-dom'
declare module 'cash-dom' {
    interface Cash {
        ensureClass(cls:any):Cash
    }
}