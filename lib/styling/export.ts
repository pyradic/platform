import {Config} from '../classes/Config';
export interface PlatformStyleVariables {
	'prefix'?:string
}
import _styleVars from  './export.scss'
export const styleVars = Config.proxied<PlatformStyleVariables>(_styleVars)