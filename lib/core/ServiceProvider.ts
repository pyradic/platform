import { Application } from './Application';
import { IServiceProvider } from '@/interfaces';

export abstract class ServiceProvider implements IServiceProvider {
    constructor(public readonly app: Application) {}
}
