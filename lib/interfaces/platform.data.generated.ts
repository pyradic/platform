export interface PlatformData {
    teams:       Team[];
    cp:          Cp;
    menus:       Menus;
    module:      Module;
    breadcrumbs: Breadcrumbs;
    user:        User;
}

export interface Breadcrumbs {
    "pyro.module.deployment::addon.name": Overzicht;
    Overzicht:                            Overzicht;
}

export interface Overzicht {
    title: string;
    url:   string;
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
    buttons:     Button[];
    attributes:  ButtonAttributes;
    permission:  string;
    breadcrumb:  null;
    hidden:      boolean;
    children?:   FluffyChild[];
}

export interface ButtonAttributes {
    href:                 string;
    ":no-submenu-icons"?: boolean;
    "data-toggle"?:       DataToggle;
    "data-target"?:       DataTarget;
    "0"?:                 string;
}

export enum DataTarget {
    Modal = "#modal",
}

export enum DataToggle {
    Modal = "modal",
}

export interface Button {
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
    buttons:     Button[];
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
    PyroExtensionLabelLinkType = "pyro.extension.label_link_type",
    PyroExtensionModuleLinkType = "pyro.extension.module_link_type",
}

export interface Module {
    id:        string;
    name:      string;
    namespace: string;
    type:      string;
}

export interface Team {
    id:               number;
    sort_order:       number;
    created_at:       Date;
    created_by_id:    number;
    updated_at:       Date;
    updated_by_id:    null;
    workspace_id:     WorkspaceID;
    account_username: string;
    account_password: string;
    meta:             TeamMeta;
    fetched_at:       Date;
    repositories:     RepositoryElement[];
}

export interface TeamMeta {
    members:      { [key: string]: Member };
    projects:     Projects;
    repositories: { [key: string]: RepositoryValue };
    displayName:  DisplayName;
    type:         OwnerType;
    username:     WorkspaceID;
    avatar:       string;
    url:          string;
}

export enum DisplayName {
    MyLink = "My-Link",
}

export interface Member {
    id:            string;
    status:        string;
    createdOn:     Date;
    displayName:   string;
    has2faEnabled: null;
    isStaff:       boolean;
    avatar:        string;
    url:           string;
    nickname:      string;
    properties:    any[];
    type:          string;
    uuid:          string;
}

export interface Projects {
    CRVS2: Alg;
    PHP:   Alg;
    KP:    Alg;
    ALG:   Alg;
    CRVS:  Alg;
}

export interface Alg {
    createdOn:   Date | null;
    description: null | string;
    isPrivate:   boolean | null;
    key:         string;
    avatar:      string;
    url:         null;
    name:        string;
    owner:       Owner | null;
    type:        OwnerType;
    updatedOn:   Date | null;
    uuid:        string;
}

export interface Owner {
    displayName: DisplayName | null;
    type:        OwnerType;
    username:    WorkspaceID | null;
    avatar:      string;
    url:         string;
}

export enum OwnerType {
    Project = "project",
    Team = "team",
}

export enum WorkspaceID {
    Mylink = "mylink",
}

export interface RepositoryValue {
    created_on:  Date;
    description: string;
    fork_policy: ForkPolicy;
    full_name:   string;
    has_issues:  boolean;
    has_wiki:    boolean;
    is_private:  boolean;
    language:    string;
    mainbranch:  Mainbranch;
    owner:       Owner;
    project:     Owner;
    scm:         SCM;
    size:        number;
    slug:        string;
    type:        RepositoryType;
    updated_on:  Date;
    website:     null | string;
    fullName:    string;
    name:        string;
    uuid:        string;
    avatar:      string;
    url:         string;
}

export enum ForkPolicy {
    AllowForks = "allow_forks",
    NoPublicForks = "no_public_forks",
}

export interface Mainbranch {
    type: MainbranchType;
    name: Name;
}

export enum Name {
    Develop = "develop",
    Master = "master",
}

export enum MainbranchType {
    Branch = "branch",
}

export enum SCM {
    Git = "git",
}

export enum RepositoryType {
    Repository = "repository",
}

export interface RepositoryElement {
    id:            number;
    sort_order:    number;
    created_at:    Date;
    created_by_id: number;
    updated_at:    Date;
    updated_by_id: null;
    name:          string;
    team_id:       number;
    meta:          RepositoryMeta;
    fetched_at:    Date;
}

export interface RepositoryMeta {
    branches:    { [key: string]: Branch };
    commits:     { [key: string]: Commit };
    created_on:  Date;
    description: string;
    fork_policy: ForkPolicy;
    full_name:   string;
    has_issues:  boolean;
    has_wiki:    boolean;
    is_private:  boolean;
    language:    string;
    mainbranch:  Mainbranch;
    owner:       Owner;
    project:     Alg;
    scm:         SCM;
    size:        number;
    slug:        string;
    type:        RepositoryType;
    updated_on:  Date;
    website:     string;
    fullName:    string;
    name:        string;
    uuid:        string;
    avatar:      string;
    url:         string;
}

export interface Branch {
    name:   string;
    target: Commit;
    url:    string;
}

export interface Commit {
    author:   Author;
    date:     Date;
    hash:     string;
    message:  string;
    rendered: Rendered | null;
    url:      string;
}

export interface Author {
    raw:          Raw;
    account_id:   AccountID;
    display_name: DisplayNameEnum;
    nickname:     Nickname;
    url:          string;
    avatar:       string;
}

export enum AccountID {
    The557058A6719252964047F1A632273Defcb23Ce = "557058:a6719252-9640-47f1-a632-273defcb23ce",
    The5C7E7Ffc3671D04Fdefcd44D = "5c7e7ffc3671d04fdefcd44d",
    The5D1B155586B1040Ce2816F93 = "5d1b155586b1040ce2816f93",
}

export enum DisplayNameEnum {
    FrNk = "Fr@nk!",
    Martha = "Martha",
    RobinRadic = "Robin Radic",
}

export enum Nickname {
    Frankroeland = "Frankroeland",
    Martha = "Martha",
    RobinRadic = "Robin Radic",
}

export enum Raw {
    FrankMeijerInfoWmomoNl = "Frank Meijer <info@wmomo.nl>",
    MarthaMarthaMyLinkNl = "martha <martha@my-link.nl>",
    RobinRadicRobinMyLinkNl = "Robin Radic <robin@my-link.nl>",
    RobinRadicRobinRadicNl = "Robin Radic <robin@radic.nl>",
    RobinRadicRradicHotmailCOM = "Robin Radic <rradic@hotmail.com>",
}

export interface Rendered {
    message: RenderedMessage;
}

export interface RenderedMessage {
    raw:    string;
    markup: Markup;
    html:   string;
    type:   MessageType;
}

export enum Markup {
    Markdown = "markdown",
}

export enum MessageType {
    Rendered = "rendered",
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
