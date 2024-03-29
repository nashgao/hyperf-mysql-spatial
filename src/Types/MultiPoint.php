<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use GeoJson\GeoJson;
use GeoJson\Geometry\MultiPoint as GeoJsonMultiPoint;
use Nashgao\HyperfMySQLSpatial\Exceptions\InvalidGeoJsonException;

class MultiPoint extends PointCollection
{
    /**
     * The minimum number of items required to create this collection.
     */
    protected int $minimumCollectionItems = 1;

    public function __toString(): string
    {
        return implode(',', array_map(function (Point $point) {
            return sprintf('(%s)', $point->toPair());
        }, $this->items));
    }

    public function toWKT(): string
    {
        return sprintf('MULTIPOINT(%s)', $this);
    }

    public static function fromWkt(string $wkt, int $srid = 0): MultiPoint
    {
        $wktArgument = Geometry::getWKTArgument($wkt);

        return static::fromString($wktArgument, $srid);
    }

    public static function fromString(string $wktArgument, int $srid = 0): self
    {
        $matches = [];
        preg_match_all('/\(\s*(\d+\s+\d+)\s*\)/', trim($wktArgument), $matches);

        $points = array_map(function ($pair) {
            return Point::fromPair($pair);
        }, $matches[1]);

        return new static($points, $srid);
    }

    public static function fromJson(GeoJson|string $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson));
        }

        if (! is_a($geoJson, GeoJsonMultiPoint::class)) {
            throw new InvalidGeoJsonException('Expected ' . GeoJsonMultiPoint::class . ', got ' . get_class($geoJson));
        }

        $set = [];
        foreach ($geoJson->getCoordinates() as $coordinate) {
            $set[] = new Point($coordinate[1], $coordinate[0]);
        }

        return new self($set);
    }

    /**
     * Convert to GeoJson MultiPoint that is jsonable to GeoJSON.
     */
    public function jsonSerialize(): \GeoJson\Geometry\Geometry
    {
        $points = [];
        foreach ($this->items as $point) {
            $points[] = $point->jsonSerialize();
        }

        return new GeoJsonMultiPoint($points);
    }
}
