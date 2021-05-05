<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Eloquent;

use Hyperf\Database\Model\Builder as HyperfBuilder;
use Nashgao\HyperfMySQLSpatial\Types\GeometryInterface;

class Builder extends HyperfBuilder
{
    public function update(array $values): int
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    protected function asWKT(GeometryInterface $geometry): SpatialExpression
    {
        return new SpatialExpression($geometry);
    }
}
