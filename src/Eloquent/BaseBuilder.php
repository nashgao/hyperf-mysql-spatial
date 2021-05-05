<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Eloquent;

use Hyperf\Database\Query\Builder as QueryBuilder;

class BaseBuilder extends QueryBuilder
{
    public function cleanBindings(array $bindings): array
    {
        $spatialBindings = [];
        foreach ($bindings as &$binding) {
            if ($binding instanceof SpatialExpression) {
                $spatialBindings[] = $binding->getSpatialValue();
                $spatialBindings[] = $binding->getSrid();
            } else {
                $spatialBindings[] = $binding;
            }
        }

        return parent::cleanBindings($spatialBindings);
    }
}
