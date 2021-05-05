<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Nashgao\HyperfMySQLSpatial\Eloquent\SpatialTrait;

/**
 * Class WithSridModel.
 *
 * @property int                                          id
 * @property \Nashgao\HyperfMySQLSpatial\Types\Point      location
 * @property \Nashgao\HyperfMySQLSpatial\Types\LineString line
 * @property \Nashgao\HyperfMySQLSpatial\Types\LineString shape
 */
class WithSridModel extends Model
{
    use SpatialTrait;

    public $timestamps = false;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];
}
