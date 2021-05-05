<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Nashgao\HyperfMySQLSpatial\Connectors\ConnectionFactory;
use Nashgao\HyperfMySQLSpatial\MysqlConnection;
use Stubs\PDOStub;

/**
 * @internal
 * @coversNothing
 */
class ConnectionFactoryBaseTest extends BaseTestCase
{
    public function testMakeCallsCreateConnection()
    {
        $pdo = new PDOStub();

        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('mysql', $pdo, 'database');

        $this->assertInstanceOf(MysqlConnection::class, $conn);
    }

    public function testCreateConnectionDifferentDriver()
    {
        $pdo = new PDOStub();

        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('pgsql', $pdo, 'database');

        $this->assertInstanceOf(\Illuminate\Database\PostgresConnection::class, $conn);
    }
}
