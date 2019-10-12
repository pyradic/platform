# Pyro Platform

Various tweaks, additions & modifications for `anomaly/streams-platform` / PyroCMS

- Login form autofill
- Path overrides
- Theme inheritance

### Login form autofill
- Uses on `ADMIN_EMAIL` and `ADMIN_PASSWORD`
- Only on local environment


### Path overrides

Provides addons a way to override views, config, languages etc of other addons or the stream-platform.  
It is based on a specific directory structure.

```
- app
- addons
    - shared
        - pyro
            - menus-module
                - src
                    - MenusModule.php
                    - MenusModuleServiceProvider.php
                - tests
                - resources
                    - config
                    - addons
                        - [vendor] pyrocms
                            - [slug] accelerant-theme
                                - views
                                    - partials
                                        - metadata.twig
                    - streams
                        - views
                            - buttons
                                - buttons.twig
                        - config
                            - 404.php
                        - lang
                    - views
                        something.twig
- bootstrap
- config
- core
    - anomaly
    - pyrocms
- database
- ...
```


```twig
{# This is the `metadata.twig` file #}
{# you can access/include the parent/overridden view like this: #}
{% include "original/pyrocms.theme.accelerant::partials.metadata" %}
```