export interface PlatformData {
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
}

export interface Breadcrumbs {
    "anomaly.module.dashboard::addon.name":               AnomalyModuleDashboardAddon;
    "anomaly.module.dashboard::addon.section.dashboards": AnomalyModuleDashboardAddon;
}

export interface AnomalyModuleDashboardAddon {
    title: string;
    url:   string;
}

export interface Cp {
    navigation: Navigation;
    shortcuts:  Shortcuts;
    buttons:    CpButton[];
}

export interface CpButton {
    key:        null;
    slug:       null | string;
    sectionKey: null;
    tag:        Tag;
    url:        string;
    text:       string;
    icon:       null | string;
    class:      null;
    type:       ButtonType;
    size:       Size;
    permission: string;
    disabled:   boolean;
    enabled:    boolean;
    attributes: ButtonAttributes;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    entry:      null;
}

export interface ButtonAttributes {
    href:                 string;
    "data-toggle"?:       DataToggle;
    "data-target"?:       DataTarget;
    ":no-submenu-icons"?: boolean;
}

export enum DataTarget {
    Modal = "#modal",
}

export enum DataToggle {
    Modal = "modal",
}

export enum Position {
    Left = "left",
}

export enum Size {
    Md = "md",
}

export enum Tag {
    A = "a",
}

export enum ButtonType {
    Default = "default",
    Info = "info",
    Success = "success",
}

export interface Navigation {
    children: NavigationChild[];
}

export interface NavigationChild {
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: PurpleAttributes;
    permission: null;
    breadcrumb: string;
    image:      Asset;
    asset:      Asset;
    children?:  PurpleChild[];
}

export interface Asset {
}

export interface PurpleAttributes {
    href:                string;
    ":no-submenu-icons": boolean;
}

export interface PurpleChild {
    key:         KeyClass | string;
    slug:        string;
    icon:        null;
    title:       string;
    label:       null;
    class:       null;
    active:      boolean;
    matcher:     null;
    permalink:   null | string;
    description: null;
    highlighted: boolean;
    context:     Context;
    parent:      null;
    buttons:     ChildElement[];
    attributes:  ButtonAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    children?:   FluffyChild[];
}

export interface ChildElement {
    key:        string;
    slug:       string;
    sectionKey: string;
    tag:        Tag;
    url:        string;
    text:       string;
    icon:       null | string;
    class:      null;
    type:       ButtonType;
    size:       Size;
    permission: string;
    disabled:   boolean;
    enabled:    boolean | string;
    attributes: ButtonAttributes;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    entry:      null;
}

export interface FluffyChild {
    key:         string;
    slug:        string;
    icon:        null;
    title:       string;
    label:       null;
    class:       null;
    active:      boolean;
    matcher:     null;
    permalink:   null;
    description: null;
    highlighted: boolean;
    context:     Context;
    parent:      string;
    buttons:     ChildElement[];
    attributes:  DepartmentAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    children?:   ChildElement[];
}

export interface DepartmentAttributes {
    href: string;
}

export enum Context {
    Danger = "danger",
}

export interface KeyClass {
    checks:   Checks;
    commands: Commands;
}

export interface Checks {
    app_key_is_set:                                  AppKeyIsSet;
    composer_with_dev_dependencies_is_up_to_date:    AppKeyIsSet;
    composer_without_dev_dependencies_is_up_to_date: AppKeyIsSet;
    configuration_is_cached:                         AppKeyIsSet;
    configuration_is_not_cached:                     AppKeyIsSet;
    correct_php_version_is_installed:                AppKeyIsSet;
    database_can_be_accessed:                        AppKeyIsSet;
    debug_mode_is_not_enabled:                       AppKeyIsSet;
    directories_have_correct_permissions:            AppKeyIsSet;
    env_file_exists:                                 AppKeyIsSet;
    example_environment_variables_are_set:           AppKeyIsSet;
    example_environment_variables_are_up_to_date:    AppKeyIsSet;
    horizon_is_running:                              HorizonIsRunning;
    locales_are_installed:                           LocalesAreInstalled;
    maintenance_mode_not_enabled:                    AppKeyIsSet;
    migrations_are_up_to_date:                       MigrationsAreUpToDate;
    php_extensions_are_disabled:                     AppKeyIsSet;
    php_extensions_are_installed:                    AppKeyIsSet;
    redis_can_be_accessed:                           RedisCanBeAccessed;
    routes_are_cached:                               AppKeyIsSet;
    routes_are_not_cached:                           AppKeyIsSet;
    servers_are_pingable:                            AppKeyIsSet;
    storage_directory_is_linked:                     AppKeyIsSet;
    supervisor_programs_are_running:                 SupervisorProgramsAreRunning;
}

export interface AppKeyIsSet {
    message: string;
    name:    string;
}

export interface HorizonIsRunning {
    message: HorizonIsRunningMessage;
    name:    string;
}

export interface HorizonIsRunningMessage {
    not_running:     string;
    unable_to_check: string;
}

export interface LocalesAreInstalled {
    message: LocalesAreInstalledMessage;
    name:    string;
}

export interface LocalesAreInstalledMessage {
    cannot_run_on_windows:        string;
    locale_command_not_available: string;
    missing_locales:              string;
    shell_exec_not_available:     string;
}

export interface MigrationsAreUpToDate {
    message: MigrationsAreUpToDateMessage;
    name:    string;
}

export interface MigrationsAreUpToDateMessage {
    need_to_migrate: string;
    unable_to_check: string;
}

export interface RedisCanBeAccessed {
    message: RedisCanBeAccessedMessage;
    name:    string;
}

export interface RedisCanBeAccessedMessage {
    not_accessible: string;
    default_cache:  string;
    named_cache:    string;
}

export interface SupervisorProgramsAreRunning {
    message: SupervisorProgramsAreRunningMessage;
    name:    string;
}

export interface SupervisorProgramsAreRunningMessage {
    cannot_run_on_windows:            string;
    not_running_programs:             string;
    shell_exec_not_available:         string;
    supervisor_command_not_available: string;
}

export interface Commands {
    self_diagnosis: SelfDiagnosis;
}

export interface SelfDiagnosis {
    common_checks:               string;
    environment_specific_checks: string;
    failed_checks:               string;
    running_check:               string;
    success:                     string;
}

export interface Shortcuts {
    department:  Department;
    preferences: Department;
}

export interface Department {
    children:    DepartmentChild[];
    type:        string;
    slug:        string;
    icon:        string;
    title:       string;
    label:       string;
    class:       null | string;
    highlighted: boolean;
    context:     Context;
    attributes:  DepartmentAttributes;
    permission:  string;
    href:        string;
}

export interface DepartmentChild {
    label: string;
    slug:  string;
    href:  string;
}

export interface Menus {
    admin_header:     AdminHeader;
    admin_pre_header: AdminHeader;
}

export interface AdminHeader {
    slug:        string;
    locale:      string;
    name:        string;
    description: string;
    children:    AdminHeaderChild[];
}

export interface AdminHeaderChild {
    id:          number;
    sort_order:  number;
    type:        PurpleType;
    entry_id:    number;
    target:      Target;
    class:       null;
    parent_id:   number | null;
    icon:        null | string;
    hash:        null | string;
    querystring: null | string;
    sdf:         null;
    url:         string;
    title:       string;
    children:    AdminHeaderChild[];
}

export enum Target {
    Blank = "_blank",
}

export enum PurpleType {
    PyroExtensionDisabledLinkType = "pyro.extension.disabled_link_type",
    PyroExtensionLabelLinkType = "pyro.extension.label_link_type",
    PyroExtensionModuleLinkType = "pyro.extension.module_link_type",
}

export interface Module {
    id:        string;
    name:      string;
    namespace: string;
    type:      string;
}

export interface User {
    id:               number;
    sort_order:       number;
    department_id:    number;
    email:            string;
    username:         string;
    display_name:     string;
    first_name:       string;
    last_name:        null;
    activated:        number;
    enabled:          number;
    permissions:      null;
    last_login_at:    Date;
    remember_token:   null;
    reset_code:       null;
    last_activity_at: Date;
    ip_address:       string;
    str_id:           string;
    gravatar:         string;
}
