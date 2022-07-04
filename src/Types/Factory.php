<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

class Factory implements \GeoIO\Factory
{
    public function createPoint($dimension, array $coordinates, ?int $srid = 0): Point
    {
        return new Point($coordinates['y'], $coordinates['x'], $srid ?? 0);
    }

    public function createLineString($dimension, array $points, ?int $srid = 0): LineString
    {
        return new LineString($points, $srid ?? 0);
    }

    public function createLinearRing($dimension, array $points, ?int $srid = 0): LineString
    {
        return new LineString($points, $srid ?? 0);
    }

    public function createPolygon($dimension, array $lineStrings, ?int $srid = 0): Polygon
    {
        return new Polygon($lineStrings, $srid ?? 0);
    }

    public function createMultiPoint($dimension, array $points, ?int $srid = 0): MultiPoint
    {
        return new MultiPoint($points, $srid ?? 0);
    }

    public function createMultiLineString($dimension, array $lineStrings, ?int $srid = 0): MultiLineString
    {
        return new MultiLineString($lineStrings, $srid ?? 0);
    }

    public function createMultiPolygon($dimension, array $polygons, ?int $srid = 0): MultiPolygon
    {
        return new MultiPolygon($polygons, $srid ?? 0);
    }

    public function createGeometryCollection($dimension, array $geometries, ?int $srid = 0): GeometryCollection
    {
        return new GeometryCollection($geometries, $srid ?? 0);
    }
}
