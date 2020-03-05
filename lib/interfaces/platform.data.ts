import { FontAwesomeIconName, FontAwesomePrefixedIconName } from '@/interfaces/icons';

export namespace Platform {
    export interface Data {
        cp: Cp;
        module: Module;
        breadcrumbs: Breadcrumb[]
        user: User;
        menus: any;

        [ key: string ]: any
    }

    export interface Breadcrumb {
        key: string
        route: {
            as:string
            uses: string
            uri: string
        }
        addon?: any
        parent: string
        title: string
        attributes:any
        class:string
    }

    export interface Cp {
        structure: Record<string, StructureNavigtion>;
        navigation: Navigation;
        section: Section;
        buttons: Button[]

        [ key: string ]: any
    }

    export interface Button {
        attributes: any
        class: string
        disabled: boolean
        dropdown: any[]
        dropup: boolean
        enabled: boolean
        icon: string
        permission: string
        position: string
        size: string
        tag: string
        text: string
        title: 'Manage Dashboards'
        type: 'info'
        url: string
    }

    export interface StructureNavigtion {
        children: StructureSection[];
        key: string;
        slug: string;
        icon: string;
        title: string;
        class: null;
        active: boolean;
        favorite: boolean;
        attributes: any;
        permission: null;
        breadcrumb: string;
        href: string;
        url: string;

        [ key: string ]: any
    }

    export interface StructureSection {
        children: StructureButton[];
        key: string;
        slug: string;
        icon: null;
        title: string;
        label: null;
        class: null;
        active: boolean;
        matcher: null;
        permalink: null;
        description: null;
        highlighted: boolean;
        context: string;
        parent: null;
        subSection: boolean;
        attributes: any
        permission: string;
        breadcrumb: null;
        hidden: boolean;
        href: string;
        url: string;

        [ key: string ]: any
    }

    export interface StructureButton {
        type: string;
        icon: string | FontAwesomeIconName | FontAwesomePrefixedIconName;
        enabled: string;
        permission: string;

        [ key: string ]: any
    }


    export interface Navigation {
        children: StructureSection[];
        key: string;
        slug: string;
        icon: string;
        title: string;
        class: null;
        active: boolean;
        favorite: boolean;
        attributes: any;
        permission: null;
        breadcrumb: string;
        href: string;
        url: string;

        [ key: string ]: any
    }

    export interface Module {
        id: string;
        name: string;
        namespace: string;
        type: string;

        [ key: string ]: any
    }

    export interface User {
        id: number;
        sort_order: number;
        email: string;
        username: string;
        display_name: string;
        first_name: string | null;
        last_name: string | null;
        activated: number;
        enabled: number;
        permissions: null;
        last_login_at: Date;
        remember_token: null;
        reset_code: null;
        last_activity_at: Date;
        ip_address: string;
        str_id: string;
        department_id: null;

        [ key: string ]: any
    }

    export interface Section {
        children: any[];
        key: string;
        slug: string;
        icon: null;
        title: string;
        label: null;
        class: null;
        active: boolean;
        matcher: null;
        permalink: string;
        description: null;
        highlighted: boolean;
        context: any;
        parent: null;
        subSection: boolean;
        buttons: any[];
        attributes: any;
        permission: string;
        breadcrumb: null;
        hidden: boolean;
        href: string;
        url: string;

        [ key: string ]: any
    }

}