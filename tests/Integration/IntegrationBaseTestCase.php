<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Test\Integration;

use Closure;
use Hyperf\DbConnection\Db;
use PHPUnit\Framework\TestCase;

abstract class IntegrationBaseTestCase extends TestCase
{
    protected $after_fix = false;

    protected $migrations = [];

    /**
     * Setup DB before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->after_fix = $this->isMySQL8AfterFix();

        $this->onMigrations(function ($migrationClass) {
            (new $migrationClass())->up();
        });

        // \DB::listen(function($sql) {
        //    var_dump($sql);
        // });
    }

    public function tearDown(): void
    {
        $this->onMigrations(function ($migrationClass) {
            (new $migrationClass())->down();
        }, true);

        parent::tearDown();
    }

    protected function assertDatabaseHas($table, array $data, $connection = null)
    {
        if (method_exists($this, 'seeInDatabase')) {
            $this->seeInDatabase($table, $data, $connection);
        } else {
            parent::assertDatabaseHas($table, $data, $connection);
        }
    }

    protected function assertException($exceptionName, $exceptionMessage = null)
    {
        if (method_exists(parent::class, 'expectException')) {
            parent::expectException($exceptionName);
            if (! is_null($exceptionMessage)) {
                $this->expectExceptionMessage($exceptionMessage);
            }
        } else {
            $this->setExpectedException($exceptionName, $exceptionMessage);
        }
    }

    // MySQL 8.0.4 fixed bug #26941370 and bug #88031
    private function isMySQL8AfterFix()
    {
        $results = Db::select(Db::raw('select version()')->getValue());
        $mysql_version = $results[0]->{'version()'};

        return version_compare($mysql_version, '8.0.4', '>=');
    }

    private function onMigrations(Closure $closure, $reverse_sort = false)
    {
        $migrations = $this->migrations;
        $reverse_sort ? rsort($migrations, SORT_STRING) : sort($migrations, SORT_STRING);

        foreach ($migrations as $migrationClass) {
            $closure($migrationClass);
        }
    }
}
