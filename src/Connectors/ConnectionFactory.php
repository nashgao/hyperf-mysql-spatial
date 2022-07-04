<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Connectors;

use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\Connectors\ConnectionFactory as HyperfConnectionFactory;
use Nashgao\HyperfMySQLSpatial\MySQLConnection;

class ConnectionFactory extends HyperfConnectionFactory
{
    /**
     * @param string $driver
     * @param \Closure|\PDO $connection
     * @param string $database
     * @param string $prefix
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = []): MySQLConnection|ConnectionInterface
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);    // @codeCoverageIgnore
        }

        if ($driver === 'mysql') {
            return new MySQLConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
