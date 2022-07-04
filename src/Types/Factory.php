<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use GeoIO\Coordinates;
use GeoIO\Dimension;

class Factory implements \GeoIO\Factory
{
    public function createPoint(Dimension $dimension, ?int $srid, ?Coordinates $coordinates): Point
    {
        return new Point($coordinates['y'], $coordinates['x'], $srid ?? 0);
    }

    public function createLineString(Dimension $dimension, ?int $srid, iterable $points): LineString
    {
        return new LineString($points, $srid ?? 0);
    }

    public function createLinearRing(Dimension $dimension, ?int $srid, iterable $points): LineString
    {
        return new LineString($points, $srid ?? 0);
    }

    public function createPolygon(Dimension $dimension, ?int $srid, iterable $lineStrings): Polygon
    {
        return new Polygon($lineStrings, $srid ?? 0);
    }

    public function createMultiPoint(Dimension $dimension, ?int $srid, iterable $points): MultiPoint
    {
        return new MultiPoint($points, $srid ?? 0);
    }

    public function createMultiLineString(Dimension $dimension, ?int $srid, iterable $lineStrings): MultiLineString
    {
        return new MultiLineString($lineStrings, $srid ?? 0);
    }

    public function createMultiPolygon(Dimension $dimension, ?int $srid, iterable $polygons): MultiPolygon
    {
        return new MultiPolygon($polygons, $srid ?? 0);
    }

    public function createGeometryCollection(Dimension $dimension, ?int $srid, iterable $geometries): GeometryCollection
    {
        return new GeometryCollection($geometries, $srid ?? 0);
    }
}
