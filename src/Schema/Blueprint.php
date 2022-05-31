<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Schema;

use Hyperf\Database\Schema\Blueprint as HyperfBlueprint;
use Hyperf\Utils\Fluent;

class Blueprint extends HyperfBlueprint
{
    /**
     * Add a geometry column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function geometry($column, int $srid = null): Fluent
    {
        return $this->addColumn('geometry', $column, compact('srid'));
    }

    /**
     * Add a point column on the table.
     *
     * @param string $column
     * @param null|int $srid
     */
    public function point($column, $srid = null): Fluent
    {
        return $this->addColumn('point', $column, compact('srid'));
    }

    /**
     * Add a linestring column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function lineString($column, int $srid = null): Fluent
    {
        return $this->addColumn('linestring', $column, compact('srid'));
    }

    /**
     * Add a polygon column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function polygon($column, int $srid = null): Fluent
    {
        return $this->addColumn('polygon', $column, compact('srid'));
    }

    /**
     * Add a multipoint column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function multiPoint($column, int $srid = null): Fluent
    {
        return $this->addColumn('multipoint', $column, compact('srid'));
    }

    /**
     * Add a multilinestring column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function multiLineString($column, int $srid = null): Fluent
    {
        return $this->addColumn('multilinestring', $column, compact('srid'));
    }

    /**
     * Add a multipolygon column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function multiPolygon($column, int $srid = null): Fluent
    {
        return $this->addColumn('multipolygon', $column, compact('srid'));
    }

    /**
     * Add a geometrycollection column on the table.
     *
     * @param string $column
     * @param int|null $srid
     */
    public function geometryCollection($column, int $srid = null): Fluent
    {
        return $this->addColumn('geometrycollection', $column, compact('srid'));
    }

    /**
     * Specify a spatial index for the table.
     *
     * @param array|string $columns
     * @param string $name
     */
    public function spatialIndex($columns, $name = null): Fluent
    {
        return $this->indexCommand('spatial', $columns, $name);
    }

    /**
     * Indicate that the given index should be dropped.
     *
     * @param array|string $index
     */
    public function dropSpatialIndex($index): Fluent
    {
        return $this->dropIndexCommand('dropIndex', 'spatial', $index);
    }
}
