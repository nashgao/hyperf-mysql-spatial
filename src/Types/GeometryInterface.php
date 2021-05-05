<?php

namespace Grimzy\LaravelMysqlSpatial\Types;

interface GeometryInterface
{
    public function toWKT();

    public static function fromWKT($wkt, $srid = 0);

    public function __toString():string;

    public static function fromString($wktArgument, $srid = 0);

    public static function fromJson($geoJson);
}
