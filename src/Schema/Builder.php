<?php

namespace Grimzy\LaravelMysqlSpatial\Schema;

use Closure;
use Hyperf\Database\Schema\MySqlBuilder;

class Builder extends MySqlBuilder
{
    /**
     * Create a new command set with a Closure.
     *
     * @param string $table
     * @param Closure|null $callback
     *
     * @return Blueprint
     */
    protected function createBlueprint($table, Closure $callback = null): Blueprint
    {
        return new Blueprint($table, $callback);
    }
}
