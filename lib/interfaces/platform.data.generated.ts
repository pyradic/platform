export interface PlatformData {
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
}

export interface Breadcrumbs {
    Menus: string;
}

export interface Cp {
    structure:  Structure;
    navigation: Navigation;
    section:    null;
}

export interface Navigation {
    children:   NavigationChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface NewDocumentClass {
    href: string;
}

export interface NavigationChild {
    children:    PurpleChild[];
    key:         string;
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
    subSection:  boolean;
    buttons:     string[] | PurpleButtons;
    attributes:  AddFieldClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface AddFieldClass {
    href:           string;
    "data-toggle"?: DataToggle;
    "data-target"?: DataTarget;
}

export enum DataTarget {
    Modal = "#modal",
}

export enum DataToggle {
    Modal = "modal",
}

export interface PurpleButtons {
    add_field: AddFieldClass;
}

export interface PurpleChild {
    key:        string;
    title:      string;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: AddFieldClass;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       null | string;
    class:      null;
    size:       Size;
    permission: string;
    type:       ManageType;
    text:       string;
    url:        null;
    tag:        Tag;
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

export enum ManageType {
    Default = "default",
    Info = "info",
    Success = "success",
}

export enum Context {
    Danger = "danger",
}

export interface Structure {
    "anomaly.module.dashboard":     AnomalyModuleDashboard;
    "anomaly.module.blocks":        AnomalyModuleBlocks;
    "crvs.module.clients":          CrvsModuleClients;
    "anomaly.module.comments":      AnomalyModuleComments;
    "crvs.module.departments":      CrvsModuleDepartments;
    "anomaly.module.documentation": AnomalyModuleDocumentation;
    "crvs.module.faq":              CrvsModuleFAQ;
    "anomaly.module.files":         AnomalyModuleFiles;
    "pyro.module.menus":            Navigation;
    "pyro.module.news":             AnomalyModuleComments;
    "anomaly.module.pages":         AnomalyModulePages;
    "anomaly.module.posts":         AnomalyModulePosts;
    "anomaly.module.settings":      AnomalyModuleComments;
    "anomaly.module.system":        AnomalyModuleSystem;
    "anomaly.module.users":         AnomalyModuleUsers;
}

export interface AnomalyModuleBlocks {
    children:   AnomalyModuleBlocksChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleBlocksChild {
    children:    PurpleChild[];
    key:         string;
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
    parent:      null | string;
    subSection:  boolean;
    buttons:     string[] | FluffyButtons;
    attributes:  AddFieldClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FluffyButtons {
    add_block?:     AddFieldClass;
    new_field?:     AddFieldClass;
    assign_fields?: AddFieldClass;
}

export interface AnomalyModuleComments {
    children:   AnomalyModuleCommentsChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleCommentsChild {
    children:    FluffyChild[];
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
    parent:      null;
    subSection:  boolean;
    buttons:     string[];
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FluffyChild {
    key:        string;
    title:      string;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: NewDocumentClass;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       null;
    class:      null;
    size:       Size;
    permission: string;
    type:       ManageType;
    text:       string;
    url:        null;
    tag:        Tag;
}

export interface AnomalyModuleDashboard {
    children:   AnomalyModuleDashboardChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleDashboardChild {
    children:    PurpleChild[];
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
    parent:      null;
    subSection:  boolean;
    buttons:     TentacledButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface TentacledButtons {
    new_dashboard?: NewDashboard;
    manage?:        Manage;
    new_widget:     NewWidget;
}

export interface Manage {
    type:       ManageType;
    icon:       Icon;
    enabled:    string;
    permission: string;
}

export enum Icon {
    FaFaPlay = "fa fa-play",
    Refresh = "refresh",
    Wrench = "wrench",
}

export interface NewDashboard {
    enabled: string;
}

export interface NewWidget {
    "data-toggle": DataToggle;
    "data-target": DataTarget;
    enabled?:      string;
    href:          string;
    permission?:   string;
}

export interface AnomalyModuleDocumentation {
    children:   AnomalyModuleDocumentationChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleDocumentationChild {
    children:    PurpleChild[];
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
    parent:      null | string;
    subSection:  boolean;
    buttons:     string[] | StickyButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface StickyButtons {
    new_project?:   AddFieldClass;
    new_field?:     AddFieldClass;
    assign_fields?: AddFieldClass;
}

export interface AnomalyModuleFiles {
    children:   AnomalyModuleFilesChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleFilesChild {
    children:    PurpleChild[];
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
    parent:      null | string;
    subSection:  boolean;
    buttons:     string[] | IndigoButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface IndigoButtons {
    upload?:        Upload;
    new_disk?:      AddFieldClass;
    new_field?:     AddFieldClass;
    assign_fields?: AddFieldClass;
}

export interface Upload {
    "data-toggle": DataToggle;
    icon:          string;
    "data-target": DataTarget;
    type:          ManageType;
    href:          string;
}

export interface AnomalyModulePages {
    children:   AnomalyModulePagesChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModulePagesChild {
    children:    PurpleChild[];
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
    parent:      null | string;
    subSection:  boolean;
    buttons:     string[] | IndecentButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface IndecentButtons {
    new_page?:      AddFieldClass;
    change_view?:   ChangeView;
    new_field?:     AddFieldClass;
    assign_fields?: AddFieldClass;
}

export interface ChangeView {
    type:    ManageType;
    enabled: string;
    icon:    string;
    href:    string;
    text:    string;
}

export interface AnomalyModulePosts {
    children:   AnomalyModulePostsChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModulePostsChild {
    children:    PurpleChild[];
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
    parent:      null | string;
    subSection:  boolean;
    buttons:     string[] | HilariousButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface HilariousButtons {
    new_post?:      AddFieldClass;
    new_field?:     AddFieldClass;
    assign_fields?: AddFieldClass;
}

export interface AnomalyModuleSystem {
    children:   AnomalyModuleSystemChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleSystemChild {
    children:    TentacledChild[];
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
    parent:      null;
    subSection:  boolean;
    buttons:     AmbitiousButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface AmbitiousButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: Actions;
}

export interface Actions {
    text:     Text;
    icon:     Icon;
    type:     ActionsType;
    href:     boolean;
    dropdown: ActionsDropdown;
}

export interface ActionsDropdown {
    "Clear All Logs":            ClearAllLogs;
    "Clear Requests Logs"?:      string;
    "Clear Commands Logs"?:      string;
    "Clear Schedule Logs"?:      string;
    "Clear Jobs Logs"?:          string;
    "Clear Exceptions Logs"?:    string;
    "Clear Logs Logs"?:          string;
    "Clear Dumps Logs"?:         string;
    "Clear Queries Logs"?:       string;
    "Clear Models Logs"?:        string;
    "Clear Events Logs"?:        string;
    "Clear Mail Logs"?:          string;
    "Clear Notifications Logs"?: string;
    "Clear Cache Logs"?:         string;
    "Flush Cache"?:              string;
}

export enum ClearAllLogs {
    AdminSystemTelescopeClear = "admin/system/telescope/clear",
}

export enum Text {
    Enable = "Enable",
    Refresh = "Refresh",
    Tools = "Tools",
}

export enum ActionsType {
    Info = "info",
    Success = "success",
    Warning = "warning",
}

export interface Refresh {
    type:     ManageType;
    icon:     Icon;
    href:     HrefEnum;
    disabled: boolean;
}

export enum HrefEnum {
    AdminMenus = "admin/menus",
}

export interface Toggle {
    type: ManageType;
    icon: Icon;
    text: Text;
}

export interface TentacledChild {
    key:        string;
    title:      Text;
    dropdown:   any[] | { [key: string]: DropdownValue };
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       Icon;
    class:      null;
    size:       Size;
    permission: string;
    type:       ActionsType;
    text:       Text;
    url:        null;
    tag:        Tag;
}

export interface PurpleAttributes {
    href: boolean | string;
}

export interface DropdownValue {
    text:       string;
    attributes: NewDocumentClass;
}

export interface AnomalyModuleUsers {
    children:   AnomalyModuleUsersChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface AnomalyModuleUsersChild {
    children:    PurpleChild[];
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
    parent:      null;
    subSection:  boolean;
    buttons:     string[] | PurpleButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface CrvsModuleClients {
    children:   CrvsModuleClientsChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface CrvsModuleClientsChild {
    children:    PurpleChild[];
    key:         string;
    slug:        string;
    icon:        null;
    title:       string;
    label:       null;
    class:       null;
    active:      boolean;
    matcher:     null;
    permalink:   null;
    description: null | string;
    highlighted: boolean;
    context:     Context;
    parent:      null;
    subSection:  boolean;
    buttons:     string[] | CunningButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface CunningButtons {
    new_document?: NewDocumentClass;
    new_role?:     NewRole;
    new_field?:    AddFieldClass;
}

export interface NewRole {
    permission: string;
}

export interface CrvsModuleDepartments {
    children:   CrvsModuleDepartmentsChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface CrvsModuleDepartmentsChild {
    children:    FluffyChild[];
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
    parent:      null;
    subSection:  boolean;
    buttons:     any[] | MagentaButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface MagentaButtons {
    new_department?:  New;
    new_association?: New;
}

export interface New {
    text:       string;
    permission: string;
}

export interface CrvsModuleFAQ {
    children:   CrvsModuleFAQChild[];
    key:        string;
    slug:       string;
    icon:       string;
    title:      string;
    class:      null;
    active:     boolean;
    favorite:   boolean;
    attributes: NewDocumentClass;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface CrvsModuleFAQChild {
    children:    StickyChild[];
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
    parent:      null;
    subSection:  boolean;
    buttons:     string[] | FriskyButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FriskyButtons {
    "0":     string;
    default: string[];
}

export interface StickyChild {
    key:        string;
    title:      string;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: FluffyAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       null;
    class:      null;
    size:       Size;
    permission: string;
    type:       ManageType;
    text:       string;
    url:        null;
    tag:        Tag;
}

export interface FluffyAttributes {
    href: string;
    "0"?: string;
}

export interface Menus {
    admin_footer:  Admin;
    admin_header:  Admin;
    admin_sidebar: Admin;
}

export interface Admin {
    slug:        string;
    locale:      string;
    name:        string;
    description: string;
    children:    AdminFooterChild[];
}

export interface AdminFooterChild {
    id:         number;
    sort_order: number;
    type:       PurpleType;
    entry_id:   number;
    target:     Target;
    class:      null;
    parent_id:  number | null;
    icon:       null | string;
    url:        null | string;
    title:      string;
    children:   AdminFooterChild[];
}

export enum Target {
    Blank = "_blank",
    Self = "_self",
}

export enum PurpleType {
    PyroExtensionCpActionLinkType = "pyro.extension.cp_action_link_type",
    PyroExtensionHeaderLinkType = "pyro.extension.header_link_type",
    PyroExtensionLabelLinkType = "pyro.extension.label_link_type",
    PyroExtensionModuleLinkType = "pyro.extension.module_link_type",
    PyroExtensionURLLinkType = "pyro.extension.url_link_type",
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
    email:            string;
    username:         string;
    display_name:     string;
    first_name:       null;
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
    department_id:    null;
}
