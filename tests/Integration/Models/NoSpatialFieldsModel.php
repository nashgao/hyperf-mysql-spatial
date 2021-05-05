<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Test\Integration\Models;

use Hyperf\Database\Model\Model;
use Nashgao\HyperfMySQLSpatial\Eloquent\SpatialTrait;

/**
 * Class NoSpatialFieldsModel.
 *
 * @property \Nashgao\HyperfMySQLSpatial\Types\Geometry geometry
 */
class NoSpatialFieldsModel extends Model
{
    use SpatialTrait;

    public $timestamps = false;

    protected $table = 'no_spatial_fields';
}
