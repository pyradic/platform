// noinspection ES6UnusedImports
import { Action, getModule, Module, Mutation, MutationAction, VuexModule } from 'vuex-module-decorators'
import { store } from './Store';

export interface IPlatformState {}

@Module({ dynamic: true, store, name: 'platform' })
export class PlatformStore extends VuexModule implements IPlatformState {
    user
    config

    @Mutation
    private SET_USER(user) { this.user = user }

    @Action
    public setUser(user) { this.SET_USER(user); }


    @Mutation
    private SET_CONFIG(config) { this.config = config }

    @Action
    public setConfig(config) { this.SET_CONFIG(config); }
}

export const PlatformStoreModule = getModule(PlatformStore)