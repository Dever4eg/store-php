<?php
// Config file for phinx (db migrations, seeds)

const BASE_PATH = __DIR__;
$config = (new \Src\Config)->loadConfigFromFile(__DIR__.'/App/configs/app.php');

return
[
    'paths' => [
        'migrations' => __DIR__ . '/app/db/migrations',
        'seeds' => __DIR__ . '/app/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'config',
        'config' => [
            'adapter' =>    $config->get('database')['adapter'],
            'host' =>       $config->get('database')['host'],
            'name' =>       $config->get('database')['name'],
            'user' =>       $config->get('database')['user'],
            'pass' =>       $config->get('database')['pass'],
            'port' =>       $config->get('database')['port'],
            'charset' =>    $config->get('database')['charset'],
        ]
    ],
    'version_order' => 'creation'
];