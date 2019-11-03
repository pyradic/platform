import Vuex from 'vuex'
import { IPlatformState } from './PlatformStore';

export interface IState {
    platform:IPlatformState
}

export const store = new Vuex.Store<IState>({

})