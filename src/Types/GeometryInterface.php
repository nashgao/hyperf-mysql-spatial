<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

interface GeometryInterface
{
    public function __toString(): string;

    public function toWKT();

    public static function fromWKT($wkt, $srid = 0);

    public static function fromString($wktArgument, $srid = 0);

    public static function fromJson($geoJson);
}
