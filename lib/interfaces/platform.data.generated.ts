export interface PlatformData {
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
    profile:     Profile;
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
    context:     string;
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
    new_field?: NewFieldClass;
}

export interface NewFieldClass {
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
    attributes: NewFieldClass;
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
    context:     string;
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
    type:       ManageType;
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
    context:     string;
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
    "anomaly.module.files":     AnomalyModuleFiles;
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
    context:     string;
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
    new_widget:     NewWidget;
}

export interface Manage {
    type:       ManageType;
    icon:       string;
    enabled:    string;
    permission: string;
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
    context:     string;
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
    new_disk?:      NewFieldClass;
    new_field?:     NewFieldClass;
    assign_fields?: NewFieldClass;
}

export interface Upload {
    "data-toggle": DataToggle;
    icon:          string;
    "data-target": DataTarget;
    type:          ManageType;
    href:          string;
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
    context:     string;
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
    add_field: NewFieldClass;
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
    context:     string;
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
    context:     string;
    parent:      null;
    subSection:  boolean;
    buttons:     string[] | TentacledButtons;
    attributes:  NewFieldClass;
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

export interface Profile {
    tabs:    Tab[];
    options: Options;
    data:    Data;
    entry:   Entry;
}

export interface Data {
    entry: Entry;
}

export interface Entry {
    id:                     number;
    sort_order:             number;
    created_at:             Date;
    created_by_id:          number;
    updated_at:             Date;
    updated_by_id:          number;
    deleted_at:             null;
    user_id:                null;
    firstname:              string;
    lastname:               string;
    initials:               null;
    maiden_name:            null;
    gender:                 string;
    birthdate:              Date;
    address:                string;
    housenumber:            string;
    postcode:               string;
    city:                   string;
    nationality:            string;
    marital_status:         string;
    phone_number:           string;
    mobile_number:          string;
    email:                  string;
    bank_account_number:    number;
    receive_correspondence: number;
    correspondence_about:   string;
    correspondence_via:     string;
    specials:               null;
    signup_date:            Date;
    memo:                   null;
    roles?:                 DepartmentElement[];
    departments?:           DepartmentElement[];
    user?:                  null;
}

export interface DepartmentElement {
    id:            number;
    sort_order:    number;
    created_at:    Date;
    created_by_id: number;
    updated_at:    Date;
    updated_by_id: number;
    deleted_at?:   null;
    name:          string;
    slug:          string;
    description:   string;
    enabled?:      number;
    color?:        string;
}

export interface Options {
    profile_view: string;
    wrapper_view: string;
    permission:   null;
}

export interface Tab {
    slug:    string;
    role:    DepartmentElement;
    actions: Action[];
    fields:  Field[];
    widgets: Widget[];
}

export interface Action {
    title:      string;
    attributes: ActionAttributes;
    visibility: Visibility;
}

export interface ActionAttributes {
    href:    string;
    target?: string;
    "0"?:    The0;
}

export interface The0 {
    "*":        boolean;
    hulpvrager: boolean;
}

export interface Visibility {
    "*":          boolean;
    mantelzorger: boolean;
    hulpvrager:   boolean;
    welzijn?:     boolean;
}

export interface Field {
    title:      null | string;
    attributes: any[];
    visibility: Visibility;
    key:        string;
    value:      boolean | number | null | string;
}

export interface Widget {
    title:      string;
    attributes: any[];
    visibility: Visibility;
    content:    string;
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
