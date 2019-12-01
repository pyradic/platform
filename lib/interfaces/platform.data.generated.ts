export interface PlatformData {
    cp:          Cp;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
    menus:       Menus;
}

export interface Breadcrumbs {
    Menus: string;
    Links: string;
}

export interface Cp {
    structure:  Structure;
    navigation: Navigation;
    section:    Section;
}

export interface Navigation {
    children:   NavigationChildren;
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

export interface NavigationChildren {
    menus:  TartuGecko;
    links:  Section;
    fields: PurpleFields;
}

export interface PurpleFields {
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
    buttons:     PurpleButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface PurpleButtons {
    add_field: AddFieldClass;
}

export interface AddFieldClass {
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

export interface PurpleChild {
    key:        string;
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: AddFieldClass;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       ChildIcon | null;
    class:      null;
    size:       Size;
    permission: string;
    type:       Type;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export enum ChildIcon {
    FaFaPlus = "fa fa-plus",
    FaFaTable = "fa fa-table",
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

export enum Type {
    Default = "default",
    Info = "info",
    Success = "success",
}

export enum Context {
    Danger = "danger",
}

export interface Section {
    children:    any[];
    key:         string;
    slug:        string;
    icon:        null;
    title:       string;
    label:       null;
    class:       null;
    active:      boolean;
    matcher:     null;
    permalink:   string;
    description: null;
    highlighted: boolean;
    context:     Context;
    parent:      null;
    subSection:  boolean;
    buttons:     any[];
    attributes:  AddFieldClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface TartuGecko {
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
    description: null | string;
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
    dropdown:   any[];
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: NewDocumentClass;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       null | string;
    class:      null;
    size:       Size;
    permission: string;
    type:       Type;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
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
    "pyro.module.news":             PyroModuleNews;
    "anomaly.module.pages":         AnomalyModulePages;
    "anomaly.module.posts":         AnomalyModulePosts;
    "anomaly.module.settings":      AnomalyModuleSettings;
    "anomaly.module.system":        AnomalyModuleSystem;
    "anomaly.module.users":         AnomalyModuleUsers;
}

export interface AnomalyModuleBlocks {
    children:   AnomalyModuleBlocksChildren;
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

export interface AnomalyModuleBlocksChildren {
    areas:       TartuGecko;
    blocks:      Blocks;
    types:       TartuGecko;
    fields:      FluffyFields;
    assignments: Assignments;
}

export interface Assignments {
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
    parent:      string;
    subSection:  boolean;
    buttons:     AssignmentsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface AssignmentsButtons {
    assign_fields: AddFieldClass;
}

export interface Blocks {
    children:    PurpleChild[];
    key:         string;
    slug:        string;
    icon:        null;
    title:       string;
    label:       null;
    class:       null;
    active:      boolean;
    matcher:     null;
    permalink:   string;
    description: null;
    highlighted: boolean;
    context:     Context;
    parent:      null;
    subSection:  boolean;
    buttons:     BlocksButtons;
    attributes:  AddFieldClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface BlocksButtons {
    add_block: AddFieldClass;
}

export interface FluffyFields {
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
    buttons:     FluffyButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FluffyButtons {
    new_field: AddFieldClass;
}

export interface AnomalyModuleComments {
    children:   AnomalyModuleCommentsChildren;
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

export interface AnomalyModuleCommentsChildren {
    comments:    TartuGecko;
    discussions: TartuGecko;
}

export interface AnomalyModuleDashboard {
    children:   AnomalyModuleDashboardChildren;
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

export interface AnomalyModuleDashboardChildren {
    dashboards: Dashboards;
    widgets:    Widgets;
}

export interface Dashboards {
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
    buttons:     DashboardsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface DashboardsButtons {
    new_dashboard: NewDashboard;
    manage:        Manage;
    new_widget:    NewWidget;
}

export interface Manage {
    type:       Type;
    icon:       ChildIcon;
    enabled:    string;
    permission: string;
}

export interface NewDashboard {
    enabled: string;
}

export interface NewWidget {
    "data-toggle": DataToggle;
    "data-target": DataTarget;
    enabled:       string;
    href:          string;
    permission:    string;
}

export interface Widgets {
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
    buttons:     WidgetsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface WidgetsButtons {
    new_widget: AddFieldClass;
}

export interface AnomalyModuleDocumentation {
    children:   AnomalyModuleDocumentationChildren;
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

export interface AnomalyModuleDocumentationChildren {
    projects:             Projects;
    categories:           TartuGecko;
    fields:               FluffyFields;
    project_assignments:  Assignments;
    category_assignments: Assignments;
}

export interface Projects {
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
    buttons:     ProjectsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface ProjectsButtons {
    new_project: AddFieldClass;
}

export interface AnomalyModuleFiles {
    children:   AnomalyModuleFilesChildren;
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

export interface AnomalyModuleFilesChildren {
    files:       Files;
    folders:     TartuGecko;
    disks:       Disks;
    fields:      FluffyFields;
    assignments: Assignments;
}

export interface Disks {
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
    buttons:     DisksButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface DisksButtons {
    new_disk: AddFieldClass;
}

export interface Files {
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
    buttons:     FilesButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface FilesButtons {
    upload: Upload;
}

export interface Upload {
    "data-toggle": DataToggle;
    icon:          ChildIcon;
    "data-target": DataTarget;
    type:          Type;
    href:          string;
}

export interface AnomalyModulePages {
    children:   AnomalyModulePagesChildren;
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

export interface AnomalyModulePagesChildren {
    pages:       Pages;
    types:       TartuGecko;
    fields:      FluffyFields;
    assignments: Assignments;
}

export interface Pages {
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
    buttons:     PagesButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface PagesButtons {
    new_page:    AddFieldClass;
    change_view: ChangeView;
}

export interface ChangeView {
    type:    Type;
    enabled: string;
    icon:    ChildIcon;
    href:    string;
    text:    string;
}

export interface AnomalyModulePosts {
    children:   AnomalyModulePostsChildren;
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

export interface AnomalyModulePostsChildren {
    posts:       Posts;
    categories:  TartuGecko;
    types:       TartuGecko;
    fields:      FluffyFields;
    assignments: Assignments;
}

export interface Posts {
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
    buttons:     PostsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface PostsButtons {
    new_post: AddFieldClass;
}

export interface AnomalyModuleSettings {
    children:   AnomalyModuleSettingsChildren;
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

export interface AnomalyModuleSettingsChildren {
    system:      TartuGecko;
    modules:     TartuGecko;
    themes:      TartuGecko;
    extensions:  TartuGecko;
    field_types: TartuGecko;
    plugins:     TartuGecko;
}

export interface AnomalyModuleSystem {
    children:   AnomalyModuleSystemChildren;
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

export interface AnomalyModuleSystemChildren {
    requests:      Requests;
    commands:      Commands;
    schedule:      Schedule;
    jobs:          Jobs;
    exceptions:    Exceptions;
    logs:          Logs;
    dumps:         Dumps;
    queries:       Queries;
    models:        Models;
    events:        Events;
    mail:          Mail;
    notifications: Notifications;
    cache:         Cache;
}

export interface Cache {
    children:    CacheChild[];
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
    buttons:     CacheButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface CacheButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: PurpleActions;
}

export interface PurpleActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: PurpleDropdown;
}

export interface PurpleDropdown {
    "Clear All Logs":   string;
    "Clear Cache Logs": string;
    "Flush Cache":      string;
}

export interface Refresh {
    type:     Type;
    icon:     RefreshIcon;
    href:     HrefEnum;
    disabled: boolean;
}

export enum HrefEnum {
    AdminMenusLinksAdminHeaderCreateCpActionLinkType = "admin/menus/links/admin_header/create/cp_action_link_type",
}

export enum RefreshIcon {
    Refresh = "refresh",
}

export interface Toggle {
    type: Type;
    icon: ToggleIcon;
    text: Text;
}

export enum ToggleIcon {
    FaFaPlay = "fa fa-play",
}

export enum Text {
    Enable = "Enable",
}

export interface CacheChild {
    key:        string;
    dropdown:   any[] | FluffyDropdown;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface PurpleAttributes {
    href: boolean | string;
}

export interface FluffyDropdown {
    "Clear All Logs":   ClearAllLogs;
    "Clear Cache Logs": ClearAllLogs;
    "Flush Cache":      ClearAllLogs;
}

export interface ClearAllLogs {
    text:       string;
    attributes: NewDocumentClass;
}

export interface Commands {
    children:    CommandsChild[];
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
    buttons:     CommandsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface CommandsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: FluffyActions;
}

export interface FluffyActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: TentacledDropdown;
}

export interface TentacledDropdown {
    "Clear All Logs":      string;
    "Clear Commands Logs": string;
}

export interface CommandsChild {
    key:        string;
    dropdown:   any[] | StickyDropdown;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface StickyDropdown {
    "Clear All Logs":      ClearAllLogs;
    "Clear Commands Logs": ClearAllLogs;
}

export interface Dumps {
    children:    DumpsChild[];
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
    buttons:     DumpsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface DumpsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: TentacledActions;
}

export interface TentacledActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: IndigoDropdown;
}

export interface IndigoDropdown {
    "Clear All Logs":   string;
    "Clear Dumps Logs": string;
}

export interface DumpsChild {
    key:        string;
    dropdown:   any[] | IndecentDropdown;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface IndecentDropdown {
    "Clear All Logs":   ClearAllLogs;
    "Clear Dumps Logs": ClearAllLogs;
}

export interface Events {
    children:    EventsChild[];
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
    buttons:     EventsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface EventsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: StickyActions;
}

export interface StickyActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: HilariousDropdown;
}

export interface HilariousDropdown {
    "Clear All Logs":    string;
    "Clear Events Logs": string;
}

export interface EventsChild {
    key:        string;
    dropdown:   any[] | AmbitiousDropdown;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface AmbitiousDropdown {
    "Clear All Logs":    ClearAllLogs;
    "Clear Events Logs": ClearAllLogs;
}

export interface Exceptions {
    children:    ExceptionsChild[];
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
    buttons:     ExceptionsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface ExceptionsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: IndigoActions;
}

export interface IndigoActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: CunningDropdown;
}

export interface CunningDropdown {
    "Clear All Logs":        string;
    "Clear Exceptions Logs": string;
}

export interface ExceptionsChild {
    key:        string;
    dropdown:   any[] | MagentaDropdown;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface MagentaDropdown {
    "Clear All Logs":        ClearAllLogs;
    "Clear Exceptions Logs": ClearAllLogs;
}

export interface Jobs {
    children:    JobsChild[];
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
    buttons:     JobsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface JobsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: IndecentActions;
}

export interface IndecentActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: FriskyDropdown;
}

export interface FriskyDropdown {
    "Clear All Logs":  string;
    "Clear Jobs Logs": string;
}

export interface JobsChild {
    key:        string;
    dropdown:   any[] | MischievousDropdown;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface MischievousDropdown {
    "Clear All Logs":  ClearAllLogs;
    "Clear Jobs Logs": ClearAllLogs;
}

export interface Logs {
    children:    LogsChild[];
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
    buttons:     LogsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface LogsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: HilariousActions;
}

export interface HilariousActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: BraggadociousDropdown;
}

export interface BraggadociousDropdown {
    "Clear All Logs":  string;
    "Clear Logs Logs": string;
}

export interface LogsChild {
    key:        string;
    dropdown:   any[] | Dropdown1;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown1 {
    "Clear All Logs":  ClearAllLogs;
    "Clear Logs Logs": ClearAllLogs;
}

export interface Mail {
    children:    MailChild[];
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
    buttons:     MailButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface MailButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: AmbitiousActions;
}

export interface AmbitiousActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: Dropdown2;
}

export interface Dropdown2 {
    "Clear All Logs":  string;
    "Clear Mail Logs": string;
}

export interface MailChild {
    key:        string;
    dropdown:   any[] | Dropdown3;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown3 {
    "Clear All Logs":  ClearAllLogs;
    "Clear Mail Logs": ClearAllLogs;
}

export interface Models {
    children:    ModelsChild[];
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
    buttons:     ModelsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface ModelsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: CunningActions;
}

export interface CunningActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: Dropdown4;
}

export interface Dropdown4 {
    "Clear All Logs":    string;
    "Clear Models Logs": string;
}

export interface ModelsChild {
    key:        string;
    dropdown:   any[] | Dropdown5;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown5 {
    "Clear All Logs":    ClearAllLogs;
    "Clear Models Logs": ClearAllLogs;
}

export interface Notifications {
    children:    NotificationsChild[];
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
    buttons:     NotificationsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface NotificationsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: MagentaActions;
}

export interface MagentaActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: Dropdown6;
}

export interface Dropdown6 {
    "Clear All Logs":           string;
    "Clear Notifications Logs": string;
}

export interface NotificationsChild {
    key:        string;
    dropdown:   any[] | Dropdown7;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown7 {
    "Clear All Logs":           ClearAllLogs;
    "Clear Notifications Logs": ClearAllLogs;
}

export interface Queries {
    children:    QueriesChild[];
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
    buttons:     QueriesButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface QueriesButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: FriskyActions;
}

export interface FriskyActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: Dropdown8;
}

export interface Dropdown8 {
    "Clear All Logs":     string;
    "Clear Queries Logs": string;
}

export interface QueriesChild {
    key:        string;
    dropdown:   any[] | Dropdown9;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown9 {
    "Clear All Logs":     ClearAllLogs;
    "Clear Queries Logs": ClearAllLogs;
}

export interface Requests {
    children:    RequestsChild[];
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
    buttons:     RequestsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface RequestsButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: MischievousActions;
}

export interface MischievousActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: Dropdown10;
}

export interface Dropdown10 {
    "Clear All Logs":      string;
    "Clear Requests Logs": string;
}

export interface RequestsChild {
    key:        string;
    dropdown:   any[] | Dropdown11;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown11 {
    "Clear All Logs":      ClearAllLogs;
    "Clear Requests Logs": ClearAllLogs;
}

export interface Schedule {
    children:    ScheduleChild[];
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
    buttons:     ScheduleButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface ScheduleButtons {
    refresh: Refresh;
    toggle:  Toggle;
    actions: BraggadociousActions;
}

export interface BraggadociousActions {
    text:     string;
    icon:     ChildIcon;
    type:     string;
    href:     boolean;
    dropdown: Dropdown12;
}

export interface Dropdown12 {
    "Clear All Logs":      string;
    "Clear Schedule Logs": string;
}

export interface ScheduleChild {
    key:        string;
    dropdown:   any[] | Dropdown13;
    dropup:     boolean;
    position:   Position;
    parent:     null;
    attributes: PurpleAttributes;
    disabled:   boolean;
    enabled:    boolean;
    entry:      null;
    icon:       string;
    class:      null;
    size:       Size;
    permission: string;
    type:       string;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface Dropdown13 {
    "Clear All Logs":      ClearAllLogs;
    "Clear Schedule Logs": ClearAllLogs;
}

export interface AnomalyModuleUsers {
    children:   AnomalyModuleUsersChildren;
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

export interface AnomalyModuleUsersChildren {
    users:  TartuGecko;
    roles:  TartuGecko;
    fields: PurpleFields;
}

export interface CrvsModuleClients {
    children:   CrvsModuleClientsChildren;
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

export interface CrvsModuleClientsChildren {
    clients:   TartuGecko;
    documents: Documents;
    roles:     Roles;
    fields:    FluffyFields;
    settings:  TartuGecko;
}

export interface Documents {
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
    buttons:     DocumentsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface DocumentsButtons {
    new_document: NewDocumentClass;
}

export interface Roles {
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
    buttons:     RolesButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface RolesButtons {
    new_role: NewRole;
}

export interface NewRole {
    permission: string;
}

export interface CrvsModuleDepartments {
    children:   CrvsModuleDepartmentsChildren;
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

export interface CrvsModuleDepartmentsChildren {
    overview:     Overview;
    associations: Associations;
    settings:     TartuGecko;
    preferences:  TartuGecko;
}

export interface Associations {
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
    buttons:     AssociationsButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface AssociationsButtons {
    new_association: New;
}

export interface New {
    text:       string;
    permission: string;
}

export interface Overview {
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
    buttons:     OverviewButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface OverviewButtons {
    new_department: New;
}

export interface CrvsModuleFAQ {
    children:   CrvsModuleFAQChildren;
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

export interface CrvsModuleFAQChildren {
    overview:   TartuGecko;
    categories: Categories;
    questions:  TartuGecko;
}

export interface Categories {
    children:    CategoriesChild[];
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
    buttons:     CategoriesButtons;
    attributes:  NewDocumentClass;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    href:        string;
    url:         string;
}

export interface CategoriesButtons {
    "0":     string;
    default: string[];
}

export interface CategoriesChild {
    key:        string;
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
    type:       Type;
    text:       string;
    url:        null;
    tag:        Tag;
    title:      string;
}

export interface FluffyAttributes {
    href: string;
    "0"?: string;
}

export interface PyroModuleNews {
    children:   PyroModuleNewsChildren;
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

export interface PyroModuleNewsChildren {
    items: TartuGecko;
}

export interface Menus {
    admin_header: AdminHeader;
}

export interface AdminHeader {
    id:            number;
    sort_order:    number;
    created_at:    Date;
    created_by_id: number;
    updated_at:    Date;
    updated_by_id: number;
    deleted_at:    null;
    slug:          string;
    locale:        string;
    name:          string;
    description:   string;
    children:      AdminHeaderChild[];
}

export interface AdminHeaderChild {
    id:         number;
    sort_order: number;
    type:       string;
    entry_id:   number;
    target:     string;
    class:      null;
    parent_id:  number | null;
    icon:       null | string;
    url:        null | string;
    title:      string;
    children:   AdminHeaderChild[];
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
