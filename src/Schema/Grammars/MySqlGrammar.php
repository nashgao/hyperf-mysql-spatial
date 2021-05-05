<?php

namespace Grimzy\LaravelMysqlSpatial\Schema\Grammars;

use Hyperf\Database\Schema\Blueprint;
use Hyperf\Utils\Fluent;
use Hyperf\Database\Schema\Grammars\MySqlGrammar as HyperfMySQLGrammar;

class MySqlGrammar extends HyperfMySQLGrammar
{
    const COLUMN_MODIFIER_SRID = 'Srid';

    public function __construct()
    {
        // Enable SRID as a column modifier
        if (!in_array(self::COLUMN_MODIFIER_SRID, $this->modifiers)) {
            $this->modifiers[] = self::COLUMN_MODIFIER_SRID;
        }
    }

    /**
     * Adds a statement to add a geometry column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typeGeometry(Fluent $column): string
    {
        return 'GEOMETRY';
    }

    /**
     * Adds a statement to add a point column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typePoint(Fluent $column): string
    {
        return 'POINT';
    }

    /**
     * Adds a statement to add a linestring column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typeLinestring(Fluent $column): string
    {
        return 'LINESTRING';
    }

    /**
     * Adds a statement to add a polygon column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typePolygon(Fluent $column): string
    {
        return 'POLYGON';
    }

    /**
     * Adds a statement to add a multipoint column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typeMultipoint(Fluent $column): string
    {
        return 'MULTIPOINT';
    }

    /**
     * Adds a statement to add a multilinestring column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typeMultilinestring(Fluent $column): string
    {
        return 'MULTILINESTRING';
    }

    /**
     * Adds a statement to add a multipolygon column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typeMultipolygon(Fluent $column): string
    {
        return 'MULTIPOLYGON';
    }

    /**
     * Adds a statement to add a geometrycollection column.
     *
     * @param Fluent $column
     *
     * @return string
     */
    public function typeGeometrycollection(Fluent $column): string
    {
        return 'GEOMETRYCOLLECTION';
    }

    /**
     * Compile a spatial index key command.
     *
     * @param Blueprint $blueprint
     * @param Fluent    $command
     *
     * @return string
     */
    public function compileSpatial(Blueprint $blueprint, Fluent $command): string
    {
        return $this->compileKey($blueprint, $command, 'spatial');
    }

    /**
     * Get the SQL for a SRID column modifier.
     *
     * @param Blueprint $blueprint
     * @param Fluent $column
     *
     * @return string|null
     */
    protected function modifySrid(Blueprint $blueprint, Fluent $column)
    {
        if (!is_null($column->srid) && is_int($column->srid) && $column->srid > 0) {
            return ' srid '.$column->srid;
        }
    }
}
