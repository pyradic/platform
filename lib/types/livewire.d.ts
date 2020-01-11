declare global {
    interface Window {
        livewire: Livewire
    }
}

export interface LivewireConstructorOptions {
    driver?: string
}

export default class Livewire {
    connection: Connection;
    components: Store;
    onLoadCallback: () => any;

    constructor(options?: LivewireConstructorOptions)

    find(componentId: string): Component

    hook(name, callback)

    onLoad(callback)

    activatePolyfills()

    emit(event, ...params)

    on(event, callback)

    restart()

    stop()

    start()

    rescan()

    beforeDomUpdate(callback)

    afterDomUpdate(callback)

    plugin(callable)
}

export interface Store {
    componentsById: Record<string, Component>
    listeners: any //new MessageBus,
    beforeDomUpdateCallback: () => {},
    afterDomUpdateCallback: () => {},
    livewireIsInBackground: boolean
    livewireIsOffline: boolean
    hooks: any // HookManager,

    components(): Component[]

    addComponent(component: Component): Component

    findComponent(id): Component

    hasComponent(id): boolean

    tearDownComponents()

    on(event, callback)

    emit(event, ...params)

    componentsListeningForEvent(event): Component[]

    registerHook(name, callback)

    callHook(name, ...params)

    beforeDomUpdate(callback)

    afterDomUpdate(callback)

    removeComponent(component)
}

export interface Driver {}

export interface ConnectionMessage {
    payload(): ConnectionMessagePayload
}

export interface ConnectionMessagePayload {
    id: any
    fromPrefetch?: boolean
}

export interface Connection {
    new(driver: Driver)

    onMessage(payload: ConnectionMessagePayload)

    onError(payloadThatFailedSending: ConnectionMessagePayload)

    sendMessage(message: ConnectionMessage)
}

export interface Component {
    data?: any
    events?: any
    children?: any
    checksum?: any
    name?: any
    errorBag?: any
    redirectTo?: any
    scopedListeners?: any
    connection?: Connection
    actionQueue?: any
    messageInTransit?: any
    modelTimeout?: any
    tearDownCallbacks?: any
    prefetchManager?: any
    id?: any
    el?: HTMLElement
    root?: HTMLElement

    new(el, connection)

    extractLivewireAttribute(name)

    initialize()

    get(name)

    set(name, value)

    call(method, ...params)

    on(event, callback)

    addAction(action)

    fireMessage()

    messageSendFailed()

    receiveMessage(payload)

    handleResponse(response)

    redirect(url)

    forceRefreshDataBoundElementsMarkedAsDirty(dirtyInputs)

    replaceDom(rawDom)

    formatDomBeforeDiffToAvoidConflictsWithVue(inputDom)

    addPrefetchAction(action)

    receivePrefetchMessage(payload)

    handleMorph(dom)

    walk(callback, callbackWhenNewComponentIsEncountered?: (el) => any)

    registerEchoListeners()

    modelSyncDebounce(callback, time)

    callAfterModelDebounce(callback)

    addListenerForTeardown(teardownCallback)

    tearDown()
}

export interface MessagePayload {
    id?: Component['id']
    data?:any
    name?: Component['name']
    checksum?: Component['checksum']
    children?: Component['children']
    actionQueue?: any
    errorBag?: any
    dom: any
    dirtyInputs:any
    eventQueue: any
    events: Component['events']
    redirectTo: Component['redirectTo']
}

export interface MessageResponse {
    id?: any
    dom?: any
    checksum?: any
    children?: any
    dirtyInputs?: any
    eventQueue?: any
    events?: any
    data?: any
    redirectTo?: any
    errorBag?: any
}

export interface Message {
    refs: any[]
    response: MessagePayload

    new(component: Component, actionQueue?: any)

    payload(): MessagePayload

    storeResponse(payload: MessagePayload): MessagePayload
}
