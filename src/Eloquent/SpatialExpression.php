<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Eloquent;

use Hyperf\Database\Query\Expression;

class SpatialExpression extends Expression
{
    public function getValue(): string
    {
        return "ST_GeomFromText(?, ?, 'axis-order=long-lat')";
    }

    public function getSpatialValue()
    {
        return $this->value->toWkt();
    }

    public function getSrid()
    {
        return $this->value->getSrid();
    }
}
