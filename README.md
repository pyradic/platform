# Pyro Platform

## Contains
### Frontend
- Core application for the admin control panel (uses [pyro/webpack](https://github.com/pyradic/webpack))

Platforms goal is to match the architecture that Laravel/PyroCMS implement whenever possible.
So stuff like the service container, service providers and even the lifecycle have a lot in common.

- `Application`
  - Platforms `Application` extends the `Container` provided by the `inversify`, a solid dependency injection library.
  - Provides DI methods a Laravel developer instantly understands:  `bind`, `singleton`, `build`, `get` and more
  - Able to `register` the `ServiceProvider` classes.

### Backend
- Various tweaks, additions & modifications for `anomaly/streams-platform` & `laravel/framework`


## Install
1. Install [pyro/webpack](https://github.com/pyradic/webpack)
1. `composer require pyro/platform`
2. `Pyro\Platform\PlatformServiceProvider`
3. 
