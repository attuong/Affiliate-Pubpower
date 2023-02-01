<?php

$_routers = [
    'backend' => ['mod' => 'frontend', 'col' => 'home', 'act' => 'index'],
    'login' => ['mod' => 'frontend', 'col' => 'home', 'act' => 'login'],
    'logout' => ['mod' => 'frontend', 'col' => 'home', 'act' => 'logout'],
    'register' => ['mod' => 'frontend', 'col' => 'home', 'act' => 'register'],
    'publisher' => ['mod' => 'frontend', 'col' => 'publisher', 'act' => 'index'],
    'domain' => ['mod' => 'frontend', 'col' => 'domain', 'act' => 'index'],
    'domain/download/(.*)' => ['mod' => 'frontend', 'col' => 'domain', 'act' => 'download', 'map' => [1 => 'file']],
    'link' => ['mod' => 'frontend', 'col' => 'link', 'act' => 'index'],
    'payment' => ['mod' => 'frontend', 'col' => 'payment', 'act' => 'index'],
    'report' => ['mod' => 'frontend', 'col' => 'report', 'act' => 'index'],
    'version/(.*)' => ['mod' => 'frontend', 'col' => 'home', 'act' => 'version', 'map' => [1 => 'version']],
];
