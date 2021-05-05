<?php

declare(strict_types=1);

namespace Grimzy\LaravelMysqlSpatial\Types;

use GeoJson\Feature\Feature;
use GeoJson\GeoJson;
use GeoJson\Geometry\Point as GeoJsonPoint;
use Grimzy\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException;

class Point extends Geometry
{
    protected float $lat;

    protected float $lng;

    final public function __construct($lat, $lng, $srid = 0)
    {
        parent::__construct($srid);

        $this->lat = (float) $lat;
        $this->lng = (float) $lng;
    }

    public function __toString(): string
    {
        return $this->getLng() . ' ' . $this->getLat();
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = (float) $lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = (float) $lng;
    }

    public function toPair(): string
    {
        return $this->getLng() . ' ' . $this->getLat();
    }

    public static function fromPair($pair, $srid = 0): Point
    {
        [$lng, $lat] = explode(' ', trim($pair, "\t\n\r \x0B()"));

        return new static((float) $lat, (float) $lng, (int) $srid);
    }

    public function toWKT(): string
    {
        return sprintf('POINT(%s)', (string) $this);
    }

    public static function fromString($wktArgument, $srid = 0): Point
    {
        return static::fromPair($wktArgument, $srid);
    }

    /**
     * @param $geoJson  Feature|string
     *
     * @return Point
     */
    public static function fromJson($geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson));
        }

        if (! is_a($geoJson, GeoJsonPoint::class)) {
            throw new InvalidGeoJsonException('Expected ' . GeoJsonPoint::class . ', got ' . get_class($geoJson));
        }

        $coordinates = $geoJson->getCoordinates();

        return new self($coordinates[1], $coordinates[0]);
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON.
     *
     * @return GeoJsonPoint
     */
    public function jsonSerialize(): \GeoJson\Geometry\Geometry
    {
        return new GeoJsonPoint([$this->getLng(), $this->getLat()]);
    }
}
