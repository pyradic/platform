import * as csx           from 'csx'
import { colors }         from '@u/colors';
import { MaterialColors } from '@/interfaces';
import { StyleTheme }     from '@/styling/styled';


export interface PlatformStyleTheme extends StyleTheme {
    colors:MaterialColors
}

export const theme:PlatformStyleTheme = {
    util: csx,
    colors: colors
}