import { streams } from './streams.generated'
export {streams}
export namespace Streams {
    export interface ClientsClient extends streams.clients_clients.ClientsClients {
        roles?: streams.clients_roles.ClientsRoles[]
        department?: streams.departments_department.DepartmentsDepartment
    }
}