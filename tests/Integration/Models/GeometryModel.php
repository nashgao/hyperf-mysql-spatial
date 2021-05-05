<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Nashgao\HyperfMySQLSpatial\Eloquent\SpatialTrait;

/**
 * Class GeometryModel.
 *
 * @property int                                          id
 * @property \Nashgao\HyperfMySQLSpatial\Types\Point      location
 * @property \Nashgao\HyperfMySQLSpatial\Types\LineString line
 * @property \Nashgao\HyperfMySQLSpatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}
