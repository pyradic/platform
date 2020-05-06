export interface PlatformData {
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: PlatformDataBreadcrumb[];
    user:        User;
    detail:      Detail;
}

export interface PlatformDataBreadcrumb {
    key:        string;
    route:      Route;
    addon:      Module;
    parent:     PurpleParent | ID;
    title:      string;
    url:        string;
    attributes: any[];
    class:      null;
}

export interface Module {
    id:        ID;
    name:      Name;
    namespace: ID;
    type:      ModuleType;
}

export enum ID {
    CrvsExtensionRequesterRoleType = "crvs.extension.requester_role_type",
    CrvsModuleClients = "crvs.module.clients",
}

export enum Name {
    CrvsModuleClientsAddonName = "crvs.module.clients::addon.name",
    HulpvragerRoleTypeExtension = "Hulpvrager Role Type Extension",
    Klanten = "Klanten",
}

export enum ModuleType {
    Extension = "extension",
    Module = "module",
}

export interface PurpleParent {
    title:      string;
    parent:     FluffyParent | ID;
    attributes: any[];
    class:      null;
    key:        string;
    route:      Route;
    bind:       any[];
    addon:      Module;
    breadcrumb: string;
    url:        null;
    entry:      null;
    truncate:   number;
    variables:  string[];
}

export interface FluffyParent {
    title:      string;
    parent:     TentacledParent | ID;
    attributes: any[];
    class:      null;
    key:        As;
    route:      Route;
    bind:       any[];
    addon:      Module;
    breadcrumb: string;
    url:        null;
    entry:      null;
    truncate:   number;
    variables:  any[];
}

export enum As {
    CrvsExtensionRequesterRoleTypeRequestsIndex = "crvs.extension.requester_role_type::requests.index",
    CrvsExtensionRequesterRoleTypeRequestsView = "crvs.extension.requester_role_type::requests.view",
    CrvsModuleClientsClientsIndex = "crvs.module.clients::clients.index",
}

export interface TentacledParent {
    title:      Name;
    parent:     ID;
    attributes: any[];
    class:      null;
    key:        As;
    route:      Route;
    bind:       any[];
    addon:      Module;
    breadcrumb: string;
    url:        null;
    entry:      null;
    truncate:   number;
    variables:  any[];
}

export interface Route {
    uri:                URI;
    methods:            Method[];
    action:             Action;
    isFallback:         boolean;
    controller:         Controller | null;
    defaults:           any[];
    wheres:             any[];
    parameters:         Parameters | null;
    parameterNames:     string[] | null;
    computedMiddleware: string[] | null;
    compiled:           Compiled;
}

export interface Action {
    as:               As;
    uses:             string;
    breadcrumb:       string[];
    uri:              URI;
    "streams::addon": ID;
    controller:       string;
    breadcrumbs?:     Array<string[] | BreadcrumbBreadcrumb>;
}

export interface BreadcrumbBreadcrumb {
    key:    string;
    title:  string;
    parent: string;
}

export enum URI {
    AdminClients = "admin/clients",
    AdminClientsRequesterRequests = "admin/clients/requester/requests",
    AdminClientsRequesterRequestsViewRequest = "admin/clients/requester/requests/view/{request}",
}

export interface Compiled {
}

export interface Controller {
    events: Compiled;
}

export enum Method {
    Delete = "DELETE",
    Get = "GET",
    Head = "HEAD",
    Options = "OPTIONS",
    Patch = "PATCH",
    Post = "POST",
    Put = "PUT",
}

export interface Parameters {
    request: Entry;
}

export interface Entry {
    id:                           number;
    sort_order:                   number;
    created_at:                   Date;
    created_by_id:                number;
    updated_at:                   Date;
    updated_by_id:                number;
    date_open:                    string;
    date_close:                   string;
    date_start:                   string;
    date_end:                     string;
    time_start:                   string;
    time_end:                     string;
    client_id:                    number;
    department_id:                number;
    availability:                 string;
    category_id:                  number;
    subject:                      string;
    match_id:                     null;
    nature:                       string;
    frequency:                    string;
    hours:                        string;
    memo:                         string;
    info:                         null;
    external_info:                null;
    is_closed:                    number;
    reason_is_closed_id:          null;
    reason_not_filled_request_id: null;
}

export interface Cp {
    navigation: Navigation;
    shortcuts:  Shortcuts;
    buttons:    any[];
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
    image:      Compiled;
    asset:      Compiled;
    children?:  PurpleChild[];
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
    buttons:     ChildButton[];
    attributes:  FluffyAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    children?:   FluffyChild[];
}

export interface FluffyAttributes {
    href:                 string;
    ":no-submenu-icons"?: boolean;
    "data-toggle"?:       DataToggle;
    "data-target"?:       DataTarget;
}

export enum DataTarget {
    Modal = "#modal",
}

export enum DataToggle {
    Modal = "modal",
}

export interface ChildButton {
    key:        string;
    slug:       string;
    sectionKey: string;
    tag:        Tag;
    url:        string;
    text:       string;
    icon:       null | string;
    class:      null;
    type:       TypeEnum;
    size:       PurpleSize;
    permission: string;
    disabled:   boolean;
    enabled:    boolean | string;
    attributes: FluffyAttributes;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    entry:      null;
}

export enum Position {
    Left = "left",
}

export enum PurpleSize {
    Md = "md",
}

export enum Tag {
    A = "a",
}

export enum TypeEnum {
    Default = "default",
    Info = "info",
    Success = "success",
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
    buttons:     ChildButton[];
    attributes:  DepartmentAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
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

export interface Detail {
    entry:   Entry;
    header:  Header;
    actions: any[];
    buttons: DetailButton[];
    widgets: Widget[];
    groups:  Group[];
    options: Options;
    layout:  DetailLayout;
}

export interface DetailButton {
    attributes: DepartmentAttributes;
    class:      null;
    disabled:   boolean;
    icon:       string;
    position:   string;
    size:       TypeEnum;
    text:       string;
    type:       string;
    url:        string;
}

export interface Group {
    key:        string;
    title:      null;
    class:      null;
    attributes: any[];
    fields:     Field[];
    header:     null;
}

export interface Field {
    title:      string;
    attributes: any[];
    class:      null | string;
    key:        string;
    value:      null | string;
    header:     boolean;
    inline:     boolean;
    layout:     FieldLayout | null;
}

export interface FieldLayout {
    name:       string;
    attributes: any[];
    children:   TentacledChild[];
    value:      null;
}

export interface TentacledChild {
    name:       string;
    attributes: TentacledAttributes;
    children:   DefaultFieldLayoutChild[];
    value:      null;
}

export interface TentacledAttributes {
    colspan: string;
}

export interface DefaultFieldLayoutChild {
    name:       string;
    attributes: StickyAttributes;
    children:   StickyChild[];
    value:      null;
}

export interface StickyAttributes {
    class: string;
}

export interface StickyChild {
    name:       string;
    attributes: IndigoAttributes;
    children:   any[];
    value:      null;
}

export interface IndigoAttributes {
    name: string;
}

export interface Header {
    title:      string;
    subtitle:   string;
    enabled:    boolean;
    class:      null;
    icon:       string;
    attributes: HeaderAttributes;
    tags:       any[];
}

export interface HeaderAttributes {
    style: string;
}

export interface DetailLayout {
    name:       string;
    attributes: any[];
    children:   IndigoChild[];
    value:      null;
}

export interface IndigoChild {
    name:       string;
    attributes: any[] | AttributesAttributes;
    children:   IndecentChild[];
    value:      null;
}

export interface AttributesAttributes {
    name:         string;
    "data-slug"?: string;
}

export interface IndecentChild {
    name:       string;
    attributes: IndecentAttributes;
    children:   HilariousChild[];
    value:      null;
}

export interface IndecentAttributes {
    name?:   string;
    gutter?: string;
    class?:  string;
    style?:  string;
}

export interface HilariousChild {
    name:       string;
    attributes: HilariousAttributes;
    children:   AmbitiousChild[];
    value:      null;
}

export interface HilariousAttributes {
    span: string;
}

export interface AmbitiousChild {
    name:       string;
    attributes: AmbitiousAttributes;
    children:   any[];
    value:      null;
}

export interface AmbitiousAttributes {
    name:   string;
    group?: string;
}

export interface Options {
    fields:                        Fields;
    profile_view:                  string;
    wrapper_view:                  string;
    "fields.hide_null":            boolean;
    "fields.transform_bool":       boolean;
    "fields.transform_bool_true":  string;
    "fields.transform_bool_false": string;
    default_field_layout:          DefaultFieldLayout;
    permission:                    null;
}

export interface DefaultFieldLayout {
    name:       string;
    attributes: any[];
    children:   DefaultFieldLayoutChild[];
    value:      null;
}

export interface Fields {
    hide_null: boolean;
}

export interface Widget {
    content:    string;
    slug:       string;
    permission: null;
    title:      string;
    disabled:   boolean;
    enabled:    boolean;
    attributes: any[];
    class:      null;
    icon:       null;
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
    type:        ChildType;
    entry_id:    number;
    target:      Target;
    class:       null;
    parent_id:   number | null;
    icon:        null | string;
    hash:        null | string;
    querystring: null | string;
    url:         string;
    title:       string;
    children:    AdminHeaderChild[];
}

export enum Target {
    Blank = "_blank",
}

export enum ChildType {
    PyroExtensionDisabledLinkType = "pyro.extension.disabled_link_type",
    PyroExtensionLabelLinkType = "pyro.extension.label_link_type",
    PyroExtensionModuleLinkType = "pyro.extension.module_link_type",
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
