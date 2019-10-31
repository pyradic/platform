<?php

// in addon sp register
$platform->addAddon($this->addon);

// in addon sp boot or controller or w/e
$platform->addScript('@pyro/menus-module');
$platform->addScript('@pyro/menus-module', 'entrySuffix'); // with package.json 'pyro.entrypoints'
$platform->addProvider('@pyro/menus-module::MenusModuleServiceProvider');
$platform->addProvider('module::MenusModuleServiceProvider');
$platform->addData('menus_module.menus');

$wpd = $platform->getWebpackData();
$wpd->
