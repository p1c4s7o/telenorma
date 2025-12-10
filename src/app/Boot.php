<?php

namespace App;

class Boot
{
    public static string $config_path = '';
    public static string $migration_path = '';
    public static string $route_path = '';
    public static string $route_ns = '';

    private static Router $_routes;

    private static Config $_config;

    private static Db $_db;

    final private function __construct()
    {
        // Dont call me
    }

    public static function init(): self
    {
        static::$_config = new Config(static::$config_path);
        static::$_routes = new Router(static::$route_path, static::$route_ns);

        if (($db = static::$_config->get('db')))
            static::$_db = new Db($db);
        return new self;
    }

    public function config(string $name)
    {
        return static::$_config->get($name);
    }

    public function db(): Db
    {
        return static::$_db;
    }

    public function run_migrations(): void
    {
        new Migrations(static::$_db, static::$migration_path);
    }

    public function listen()
    {
        static::$_routes->listen($this);
    }

}