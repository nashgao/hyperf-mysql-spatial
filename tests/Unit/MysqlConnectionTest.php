<?php

declare(strict_types=1);

use Nashgao\HyperfMySQLSpatial\MysqlConnection;
use Nashgao\HyperfMySQLSpatial\Schema\Builder;
use PHPUnit\Framework\TestCase;
use Stubs\PDOStub;

/**
 * @internal
 * @coversNothing
 */
class MysqlConnectionTest extends TestCase
{
    private $mysqlConnection;

    protected function setUp()
    {
        $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->mysqlConnection = new MysqlConnection(new PDOStub(), 'database', 'prefix', $mysqlConfig);
    }

    public function testGetSchemaBuilder()
    {
        $builder = $this->mysqlConnection->getSchemaBuilder();

        $this->assertInstanceOf(Builder::class, $builder);
    }
}
