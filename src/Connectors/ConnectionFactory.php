<?php

declare(strict_types=1);

namespace Grimzy\LaravelMysqlSpatial\Connectors;

use Grimzy\LaravelMysqlSpatial\MysqlConnection;
use Hyperf\Database\Connectors\ConnectionFactory as HyperfConnectionFactory;
use Illuminate\Database\ConnectionInterface;
use PDO;

class ConnectionFactory extends HyperfConnectionFactory
{
    /**
     * @param string $driver
     * @param \Closure|PDO $connection
     * @param string $database
     * @param string $prefix
     *
     * @return ConnectionInterface
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);    // @codeCoverageIgnore
        }

        if ($driver === 'mysql') {
            return new MysqlConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
