<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use GeoIO\WKB\Parser\Parser;
use GeoJson\GeoJson;
use Hyperf\Contract\Jsonable;
use Nashgao\HyperfMySQLSpatial\Exceptions\UnknownWKTTypeException;

abstract class Geometry implements GeometryInterface, Jsonable, \JsonSerializable
{
    protected static array $wkb_types = [
        1 => Point::class,
        2 => LineString::class,
        3 => Polygon::class,
        4 => MultiPoint::class,
        5 => MultiLineString::class,
        6 => MultiPolygon::class,
        7 => GeometryCollection::class,
    ];

    protected int $srid;

    public function __construct(int $srid = 0)
    {
        $this->srid = $srid;
    }

    public function getSrid(): int
    {
        return $this->srid;
    }

    public function setSrid(int $srid)
    {
        $this->srid = $srid;
    }

    public static function getWKTArgument(string $value): string
    {
        $left = strpos($value, '(');
        $right = strrpos($value, ')');

        return substr($value, $left + 1, $right - $left - 1);
    }

    public static function getWKTClass(string $value): string
    {
        $left = strpos($value, '(');
        $type = trim(substr($value, 0, $left));

        return match (strtoupper($type)) {
            'POINT' => Point::class,
            'LINESTRING' => LineString::class,
            'POLYGON' => Polygon::class,
            'MULTIPOINT' => MultiPoint::class,
            'MULTILINESTRING' => MultiLineString::class,
            'MULTIPOLYGON' => MultiPolygon::class,
            'GEOMETRYCOLLECTION' => GeometryCollection::class,
            default => throw new UnknownWKTTypeException('Type was ' . $type),
        };
    }

    public static function fromWKB(string $wkb): Geometry
    {
        $srid = substr($wkb, 0, 4);
        $srid = unpack('L', $srid)[1];

        $wkb = substr($wkb, 4);
        $parser = new Parser(new Factory());

        /** @var Geometry $parsed */
        $parsed = $parser->parse($wkb);

        if ($srid > 0) {
            $parsed->setSrid($srid);
        }

        return $parsed;
    }

    public static function fromWKT(string $wkt, int $srid = 0)
    {
        $wktArgument = static::getWKTArgument($wkt);

        return static::fromString($wktArgument, $srid);
    }

    public static function fromJson(GeoJson|string $geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson));
        }

        if ($geoJson->getType() === 'FeatureCollection') {
            return GeometryCollection::fromJson($geoJson);
        }

        if ($geoJson->getType() === 'Feature') {
            $geoJson = $geoJson->getGeometry();
        }

        /** @var Geometry $type */
        $type = '\Nashgao\HyperfMySQLSpatial\Types\\' . $geoJson->getType();

        return $type::fromJson($geoJson);
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this, $options);
    }
}
