export interface PlatformData {
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
    profile:     PlatformDataProfile;
}

export interface Breadcrumbs {
    "Klant Module": string;
    Klanten:        string;
}

export interface Cp {
    structure:  Structure;
    navigation: Navigation;
    section:    Section;
    shortcuts:  Shortcuts;
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
    attributes: PurpleAttributes;
    permission: null;
    breadcrumb: string;
    href:       string;
    url:        string;
}

export interface PurpleAttributes {
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
    permalink:   null;
    description: null | string;
    highlighted: boolean;
    context:     Context;
    parent:      null;
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
    new_role?:  NewRole;
    new_field?: NewField;
}

export interface NewField {
    "data-toggle"?: DataToggle;
    "data-target"?: DataTarget;
    href:           string;
}

export enum DataTarget {
    Modal = "#modal",
}

export enum DataToggle {
    Modal = "modal",
}

export interface NewRole {
    permission: string;
}

export interface PurpleChild {
    key:        string;
    title:      string;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: NewField;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       Icon | null;
    class:      null;
    size:       Size;
    permission: string;
    type:       SlugEnum;
    text:       string;
    url:        null;
    tag:        Tag;
}

export enum Icon {
    FaFaPlus = "fa fa-plus",
    Upload = "upload",
    Wrench = "wrench",
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

export enum SlugEnum {
    Default = "default",
    Info = "info",
    Success = "success",
}

export enum Context {
    Danger = "danger",
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
    description: null | string;
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

export interface SectionChild {
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
    type:       SlugEnum;
    text:       string;
    url:        null;
    tag:        Tag;
}

export interface Shortcuts {
    department:  PreferencesClass;
    preferences: PreferencesClass;
}

export interface PreferencesClass {
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
    "anomaly.module.dashboard": AnomalyModuleDashboard;
    "pyro.module.activity_log": AnomalyModuleSettings;
    "crvs.module.clients":      Navigation;
    "crvs.module.departments":  CrvsModuleDepartments;
    "examples.module.ex3":      ExamplesModuleEx3;
    "anomaly.module.files":     AnomalyModuleFiles;
    "anomaly.module.grids":     AnomalyModuleGrids;
    "pyro.module.menus":        PyroModuleMenus;
    "anomaly.module.settings":  AnomalyModuleSettings;
    "anomaly.module.users":     AnomalyModuleUsers;
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
    attributes: PurpleAttributes;
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
    buttons:     ChildButtonsClass;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface ChildButtonsClass {
    new_dashboard?: NewDashboard;
    manage?:        Manage;
    new_widget:     NewWidgetClass;
}

export interface Manage {
    type:       SlugEnum;
    icon:       Icon;
    enabled:    string;
    permission: string;
}

export interface NewDashboard {
    enabled: string;
}

export interface NewWidgetClass {
    "data-toggle"?: DataToggle;
    "data-target"?: DataTarget;
    enabled?:       string;
    href:           string;
    permission?:    string;
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
    attributes: PurpleAttributes;
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
    buttons:     string[] | FluffyButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FluffyButtons {
    upload?:        Upload;
    new_disk?:      NewField;
    new_field?:     NewField;
    assign_fields?: NewField;
}

export interface Upload {
    "data-toggle": DataToggle;
    icon:          Icon;
    "data-target": DataTarget;
    type:          SlugEnum;
    href:          string;
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
    buttons:     string[] | TentacledButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface TentacledButtons {
    new_field?:     NewWidgetClass;
    assign_fields?: NewWidgetClass;
}

export interface AnomalyModuleSettings {
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
    buttons:     string[] | StickyButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface StickyButtons {
    add_field: NewField;
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
    buttons:     any[] | IndigoButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface IndigoButtons {
    new_department?:  New;
    new_association?: New;
}

export interface New {
    text:       string;
    permission: string;
}

export interface ExamplesModuleEx3 {
    children:   ExamplesModuleEx3Child[];
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

export interface ExamplesModuleEx3Child {
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
    buttons:     any[] | IndecentButtons;
    attributes:  PurpleAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface IndecentButtons {
    s: string[];
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
    type:       SlugEnum;
    text:       string;
    url:        null;
    tag:        Tag;
}

export interface FluffyAttributes {
    "0":  string;
    href: string;
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
    buttons:     string[] | StickyButtons;
    attributes:  NewWidgetClass;
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
}

export enum PurpleType {
    PyroExtensionCpActionLinkType = "pyro.extension.cp_action_link_type",
    PyroExtensionDividerLinkType = "pyro.extension.divider_link_type",
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

export interface PlatformDataProfile {
    entry:       Entry;
    roles:       RoleClass[];
    departments: RoleClass[];
    department:  RoleClass;
    profiles:    ProfileElement[];
}

export interface RoleClass {
    id:          number;
    name:        string;
    slug:        string;
    description: string;
    enabled?:    boolean;
    color?:      string;
}

export interface Entry {
    id:         number;
    firstname:  string;
    lastname:   string;
    gender:     string;
    created_at: Date;
}

export interface ProfileElement {
    actions: Action[];
    role:    string;
    widgets: Widget[];
    fields:  Field[];
}

export interface Action {
    active:     boolean;
    slug:       string;
    permission: null;
    text:       string;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    attributes: PurpleAttributes;
    class:      null;
}

export interface Field {
    slug:       SlugEnum;
    title:      string;
    attributes: any[];
    class:      null;
    key:        string;
    value:      null | string;
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
