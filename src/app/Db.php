<?php

namespace App;

use PDO;

class Db
{
    static ?PDO $_connect = null;

    private function build_dsn(string $driver, string $host, string $dbname, int $port = 3306): string
    {
        return "$driver:host=$host:$port;dbname=$dbname";
    }

    public function __construct(array $config)
    {
        if (!is_null(static::$_connect)) throw new \RuntimeException('Instance db already connected');
        static::$_connect = new PDO($this->build_dsn($config['driver'], $config['host'], $config['db_name'], $config['port']),
            $config['user'], $config['password'], $config['attributes']);
    }

    public function begin(): bool
    {
        return static::$_connect->beginTransaction();
    }

    public function commit(): bool
    {
        return static::$_connect->commit();
    }

    public function inTx(): bool
    {
        return static::$_connect->inTransaction();
    }

    public function lastInsert($name = 'id'): false|string
    {
        return static::$_connect->lastInsertId($name);
    }

    public function rollBack(): bool
    {
        return static::$_connect->rollBack();
    }

    public function prepare(string $query, array $vars = []): false|\PDOStatement
    {
        return static::$_connect->prepare($query, $vars);
    }

    public function exec($sql): false|int
    {
        return static::$_connect->exec($sql);
    }
}