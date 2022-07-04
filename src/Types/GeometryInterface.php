<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use GeoJson\GeoJson;

interface GeometryInterface
{
    public function __toString(): string;

    public function toWKT();

    public static function fromWKT(string $wkt, int $srid = 0);

    public static function fromString(string $wktArgument, int $srid = 0);

    public static function fromJson(string|GeoJson $geoJson);
}
