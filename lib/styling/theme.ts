import { StyleTheme } from '@/decorators/styles';
import * as csx from 'csx'
import { colors } from '@u/colors';
import { MaterialColors } from '@/interfaces';


export interface PlatformStyleTheme extends StyleTheme {
    colors:MaterialColors
}

export const theme:PlatformStyleTheme = {
    util: csx,
    colors: colors
}