<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

class Factory implements \GeoIO\Factory
{
    public function createPoint($dimension, array $coordinates,  $srid = 0): Point
    {
        return new Point($coordinates['y'], $coordinates['x'], $srid);
    }

    public function createLineString($dimension, array $points,  $srid = 0): LineString
    {
        return new LineString($points, $srid);
    }

    public function createLinearRing($dimension, array $points, $srid = 0): LineString
    {
        return new LineString($points, $srid);
    }

    public function createPolygon($dimension, array $lineStrings, $srid = 0): Polygon
    {
        return new Polygon($lineStrings, $srid);
    }

    public function createMultiPoint($dimension, array $points, $srid = 0): MultiPoint
    {
        return new MultiPoint($points, $srid);
    }

    public function createMultiLineString($dimension, array $lineStrings, $srid = 0): MultiLineString
    {
        return new MultiLineString($lineStrings, $srid);
    }

    public function createMultiPolygon($dimension, array $polygons, $srid = 0): MultiPolygon
    {
        return new MultiPolygon($polygons, $srid);
    }

    public function createGeometryCollection($dimension, array $geometries, $srid = 0): GeometryCollection
    {
        return new GeometryCollection($geometries, $srid);
    }
}
