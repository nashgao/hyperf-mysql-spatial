<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use GeoJson\GeoJson;
use GeoJson\Geometry\MultiPolygon as GeoJsonMultiPolygon;
use Nashgao\HyperfMySQLSpatial\Exceptions\InvalidGeoJsonException;

class MultiPolygon extends GeometryCollection
{
    /**
     * The minimum number of items required to create this collection.
     */
    protected int $minimumCollectionItems = 1;

    /**
     * The class of the items in the collection.
     */
    protected string $collectionItemType = Polygon::class;

    public function __toString(): string
    {
        return implode(',', array_map(function (Polygon $polygon) {
            return sprintf('(%s)', $polygon);
        }, $this->items));
    }

    public function toWKT(): string
    {
        return sprintf('MULTIPOLYGON(%s)', $this);
    }

    public static function fromString(string $wktArgument, int $srid = 0): self
    {
        $parts = preg_split('/(\)\s*\)\s*,\s*\(\s*\()/', $wktArgument, -1, PREG_SPLIT_DELIM_CAPTURE);
        $polygons = static::assembleParts($parts);

        return new static(array_map(function ($polygonString) {
            return Polygon::fromString($polygonString);
        }, $polygons), $srid);
    }

    /**
     * Get the polygons that make up this MultiPolygon.
     *
     * @return array|Polygon[]
     */
    public function getPolygons(): array
    {
        return $this->items;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateItemType($value);

        parent::offsetSet($offset, $value);
    }

    public static function fromJson(GeoJson|string $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson));
        }

        if (! is_a($geoJson, GeoJsonMultiPolygon::class)) {
            throw new InvalidGeoJsonException('Expected ' . GeoJsonMultiPolygon::class . ', got ' . get_class($geoJson));
        }

        $set = [];
        foreach ($geoJson->getCoordinates() as $polygonCoordinates) {
            $lineStrings = [];
            foreach ($polygonCoordinates as $lineStringCoordinates) {
                $points = [];
                foreach ($lineStringCoordinates as $lineStringCoordinate) {
                    $points[] = new Point($lineStringCoordinate[1], $lineStringCoordinate[0]);
                }
                $lineStrings[] = new LineString($points);
            }
            $set[] = new Polygon($lineStrings);
        }

        return new self($set);
    }

    /**
     * Convert to GeoJson MultiPolygon that is jsonable to GeoJSON.
     */
    public function jsonSerialize(): GeoJsonMultiPolygon
    {
        $polygons = [];
        foreach ($this->items as $polygon) {
            $polygons[] = $polygon->jsonSerialize();
        }

        return new GeoJsonMultiPolygon($polygons);
    }

    /**
     * Make an array like this:
     * "((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1",
     * ")), ((",
     * "-1 -1,-1 -2,-2 -2,-2 -1,-1 -1",
     * ")), ((",
     * "-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))".
     *
     * Into:
     * "((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1))",
     * "((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))",
     * "((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))"
     */
    protected static function assembleParts(array $parts): array
    {
        $polygons = [];
        $count = count($parts);

        for ($i = 0; $i < $count; ++$i) {
            if ($i % 2 !== 0) {
                [$end, $start] = explode(',', $parts[$i]);
                $polygons[$i - 1] .= $end;
                $polygons[++$i] = $start . $parts[$i];
            } else {
                $polygons[] = $parts[$i];
            }
        }

        return $polygons;
    }
}
