<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use GeoJson\Feature\Feature;
use GeoJson\GeoJson;
use GeoJson\Geometry\Point as GeoJsonPoint;
use Nashgao\HyperfMySQLSpatial\Exceptions\InvalidGeoJsonException;

// todo: wip
class CartesianPoint extends Geometry
{
    protected float $x;

    protected float $y;

    final public function __construct($x, $y, $srid = 0)
    {
        parent::__construct($srid);

        $this->x = (float) $x;
        $this->y = (float) $y;
    }

    public function __toString(): string
    {
        return $this->getX() . ' ' . $this->getY();
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function setX(float $x): void
    {
        $this->x = $x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setY(float $y): void
    {
        $this->y = $y;
    }

    public static function fromPair($pair, $srid = 0): CartesianPoint
    {
        [$lng, $lat] = explode(' ', trim($pair, "\t\n\r \x0B()"));

        return new static((float) $lat, (float) $lng, (int) $srid);
    }

    public function toWKT(): string
    {
        return sprintf('POINT(%s)', (string) $this);
    }

    public static function fromString($wktArgument, $srid = 0): CartesianPoint
    {
        return static::fromPair($wktArgument, $srid);
    }

    /**
     * @param $geoJson Feature|string
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
        return new GeoJsonPoint([$this->getX(), $this->getY()]);
    }
}
