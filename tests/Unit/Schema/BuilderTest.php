<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Test\Unit\Schema;

use Mockery;
use Nashgao\HyperfMySQLSpatial\MySQLConnection;
use Nashgao\HyperfMySQLSpatial\Schema\Blueprint;
use Nashgao\HyperfMySQLSpatial\Schema\Builder;
use Nashgao\HyperfMySQLSpatial\Test\Unit\BaseTestCase;

/**
 * @internal
 * @coversNothing
 */
class BuilderTest extends BaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = Mockery::mock(MySQLConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
