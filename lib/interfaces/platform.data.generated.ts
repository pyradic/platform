export interface PlatformData {
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
}

export interface Breadcrumbs {
    "Dashboard Module": string;
    Dashboards:         string;
}

export interface Cp {
    structure:  Structure;
    navigation: Navigation;
    section:    Section;
    shortcuts:  Shortcuts;
}

export interface Navigation {
    children:   Section[];
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
    href:       string;
    url:        string;
}

export interface PurpleAttributes {
    href: string;
}

export interface Section {
    children:    SectionChild[];
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
    buttons:     SectionButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface SectionButtons {
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
    FaFaPlus = "fa fa-plus",
    Upload = "upload",
    Wrench = "wrench",
}

export enum ManageType {
    Default = "default",
    Info = "info",
    Success = "success",
}

export interface NewDashboard {
    enabled: string;
}

export interface NewWidget {
    "data-toggle"?: DataToggle;
    "data-target"?: DataTarget;
    enabled?:       string;
    href:           string;
    permission?:    string;
}

export enum DataTarget {
    Modal = "#modal",
}

export enum DataToggle {
    Modal = "modal",
}

export interface SectionChild {
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
    icon:       Icon | null;
    class:      null;
    size:       Size;
    permission: string;
    type:       ManageType;
    text:       string;
    url:        null;
    tag:        Tag;
}

export interface AddFieldClass {
    href:           string;
    "data-toggle"?: DataToggle;
    "data-target"?: DataTarget;
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

export enum Context {
    Danger = "danger",
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
    attributes:  PurpleAttributes;
    permission:  string;
    href:        string;
}

export interface DepartmentChild {
    label: string;
    slug:  string;
    href:  string;
}

export interface Structure {
    "anomaly.module.dashboard":  Navigation;
    "crvs.module.activities":    AnomalyModuleSettings;
    "pyro.module.activity_log":  AnomalyModuleSettings;
    "crvs.module.clients":       CrvsModuleClients;
    "crvs.module.contacts":      AnomalyModuleSettings;
    "crvs.module.departments":   CrvsModuleDepartments;
    "crvs.module.faq":           CrvsModuleFAQ;
    "crvs.module.files":         CrvsModuleFiles;
    "anomaly.module.grids":      AnomalyModuleGrids;
    "crvs.module.help_requests": AnomalyModuleSettings;
    "pyro.module.menus":         PyroModuleMenus;
    "crvs.module.registrations": Navigation;
    "anomaly.module.settings":   AnomalyModuleSettings;
    "anomaly.module.users":      AnomalyModuleUsers;
}

export interface AnomalyModuleGrids {
    children:   AnomalyModuleGridsChild[];
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
    href:       string;
    url:        string;
}

export interface AnomalyModuleGridsChild {
    children:    SectionChild[];
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
    buttons:     string[] | PurpleButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface PurpleButtons {
    new_field?:     NewWidget;
    assign_fields?: NewWidget;
}

export interface AnomalyModuleSettings {
    children:   AnomalyModuleSettingsChild[];
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
    href:       string;
    url:        string;
}

export interface AnomalyModuleSettingsChild {
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
    buttons:     string[];
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface PurpleChild {
    key:        string;
    title:      string;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
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

export interface AnomalyModuleUsers {
    children:   AnomalyModuleUsersChild[];
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
    href:       string;
    url:        string;
}

export interface AnomalyModuleUsersChild {
    children:    SectionChild[];
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
    buttons:     string[] | FluffyButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FluffyButtons {
    add_field: AddFieldClass;
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
    attributes: PurpleAttributes;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface CrvsModuleClientsChild {
    children:    SectionChild[];
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
    buttons:     string[] | TentacledButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface TentacledButtons {
    new_role?:  NewRole;
    new_field?: NewWidget;
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
    attributes: PurpleAttributes;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface CrvsModuleDepartmentsChild {
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
    buttons:     any[] | StickyButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface StickyButtons {
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
    attributes: PurpleAttributes;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface CrvsModuleFAQChild {
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
    buttons:     string[] | IndigoButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface IndigoButtons {
    "0":     string;
    default: string[];
}

export interface FluffyChild {
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

export interface CrvsModuleFiles {
    children:   CrvsModuleFilesChild[];
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
    href:       string;
    url:        string;
}

export interface CrvsModuleFilesChild {
    children:    SectionChild[];
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
    buttons:     string[] | IndecentButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface IndecentButtons {
    upload?:    Upload;
    new_disk?:  AddFieldClass;
    new_field?: AddFieldClass;
}

export interface Upload {
    "data-toggle": DataToggle;
    icon:          Icon;
    "data-target": DataTarget;
    type:          ManageType;
    href:          string;
}

export interface PyroModuleMenus {
    children:   PyroModuleMenusChild[];
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
    href:       string;
    url:        string;
}

export interface PyroModuleMenusChild {
    children:    SectionChild[];
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
    buttons:     string[] | FluffyButtons;
    attributes:  NewWidget;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
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
    url:         string;
    title:       string;
    children:    AdminFooterChild[];
}

export enum Target {
    Blank = "_blank",
}

export enum PurpleType {
    PyroExtensionCpActionLinkType = "pyro.extension.cp_action_link_type",
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
    department_id:    number;
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
    gravatar:         string;
}
