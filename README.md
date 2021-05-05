# Hyperf MySQL Spatial extension
Spatial data type support for [hyperf/database](https://github.com/hyperf/database.git)

Transplanted from [grimzy/laravel-mysql-spatial](https://github.com/grimzy/laravel-mysql-spatial)


## Installation

Add the package using composer:

```sh
$ composer require nashgao/hyperf-mysql-spatial:~0.1
```

Then edit the model you just created. It must use the `SpatialTrait` and define an array called `$spatialFields` with the name of the MySQL Spatial Data field(s) created in the migration:

```php
namespace App;

use Nashgao\HyperfMySQLSpatial\Eloquent\SpatialTrait;
use Hyperf\Database\Model\Model;
/**
 * @property \Nashgao\HyperfMySQLSpatial\Types\Point   $location
 * @property \Nashgao\HyperfMySQLSpatial\Types\Polygon $area
 */
class Place extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'name'
    ];

    protected $spatialFields = [
        'location',
        'area'
    ];
}
```

### Saving a model

```php
use Nashgao\HyperfMySQLSpatial\Types\Point;
use Nashgao\HyperfMySQLSpatial\Types\Polygon;
use Nashgao\HyperfMySQLSpatial\Types\LineString;

$place1 = new Place();
$place1->name = 'Empire State Building';

// saving a point
$place1->location = new Point(40.7484404, -73.9878441);	// (lat, lng)
$place1->save();

// saving a polygon
$place1->area = new Polygon([new LineString([
    new Point(40.74894149554006, -73.98615270853043),
    new Point(40.74848633046773, -73.98648262023926),
    new Point(40.747925497790725, -73.9851602911949),
    new Point(40.74837050671544, -73.98482501506805),
    new Point(40.74894149554006, -73.98615270853043)
])]);
$place1->save();
```

Or if your database fields were created with a specific SRID:

```php
use Nashgao\HyperfMySQLSpatial\Types\Point;
use Nashgao\HyperfMySQLSpatial\Types\Polygon;
use Nashgao\HyperfMySQLSpatial\Types\LineString;

$place1 = new Place();
$place1->name = 'Empire State Building';

// saving a point with SRID 4326 (WGS84 spheroid)
$place1->location = new Point(40.7484404, -73.9878441, 4326);	// (lat, lng, srid)
$place1->save();

// saving a polygon with SRID 4326 (WGS84 spheroid)
$place1->area = new Polygon([new LineString([
    new Point(40.74894149554006, -73.98615270853043),
    new Point(40.74848633046773, -73.98648262023926),
    new Point(40.747925497790725, -73.9851602911949),
    new Point(40.74837050671544, -73.98482501506805),
    new Point(40.74894149554006, -73.98615270853043)
])], 4326);
$place1->save();
```

> **Note**: When saving collection Geometries (`LineString`, `Polygon`, `MultiPoint`, `MultiLineString`, and `GeometryCollection`), only the top-most geometry should have an SRID set in the constructor.
>
> In the example above, when creating a `new Polygon()`, we only set the SRID on the `Polygon` and use the default for the `LineString` and the `Point` objects.

### Retrieving a model

```php
$place2 = Place::first();
$lat = $place2->location->getLat();	// 40.7484404
$lng = $place2->location->getLng();	// -73.9878441
```

## Geometry classes

### Available Geometry classes

| Nashgao\HyperfMySQLSpatial\Types                             | OpenGIS Class                                                |
| ------------------------------------------------------------ | ------------------------------------------------------------ |
| `Point($lat, $lng, $srid = 0)`                               | [Point](https://dev.mysql.com/doc/refman/8.0/en/gis-class-point.html) |
| `MultiPoint(Point[], $srid = 0)`                             | [MultiPoint](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipoint.html) |
| `LineString(Point[], $srid = 0)`                             | [LineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-linestring.html) |
| `MultiLineString(LineString[], $srid = 0)`                   | [MultiLineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multilinestring.html) |
| `Polygon(LineString[], $srid = 0)` *([exterior and interior boundaries](https://dev.mysql.com/doc/refman/8.0/en/gis-class-polygon.html))* | [Polygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-polygon.html) |
| `MultiPolygon(Polygon[], $srid = 0)`                         | [MultiPolygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipolygon.html) |
| `GeometryCollection(Geometry[], $srid = 0)`                  | [GeometryCollection](https://dev.mysql.com/doc/refman/8.0/en/gis-class-geometrycollection.html) |

Check out the [Class diagram](https://user-images.githubusercontent.com/1837678/30788608-a5afd894-a16c-11e7-9a51-0a08b331d4c4.png).

### Using Geometry classes

In order for your Eloquent Model to handle the Geometry classes, it must use the `Nashgao\HyperfMySQLSpatial\Eloquent\SpatialTrait` trait and define a `protected` property `$spatialFields`  as an array of MySQL Spatial Data Type column names (example in [Quickstart](#user-content-create-a-model)).

#### IteratorAggregate and ArrayAccess

The collection Geometries (`LineString`, `Polygon`, `MultiPoint`, `MultiLineString`, and `GeometryCollection`) implement [`IteratorAggregate`](http://php.net/manual/en/class.iteratoraggregate.php) and [`ArrayAccess`](http://php.net/manual/en/class.arrayaccess.php); making it easy to perform Iterator and Array operations. For example:

```php
$polygon = $multipolygon[10];	// ArrayAccess

// IteratorAggregate
for($polygon as $i => $linestring) {
  echo (string) $linestring;
}

```

#### Helpers

##### From/To Well Known Text ([WKT](https://dev.mysql.com/doc/refman/8.0/en/gis-data-formats.html#gis-wkt-format))

```php
// fromWKT($wkt, $srid = 0)
$point = Point::fromWKT('POINT(2 1)');
$point->toWKT();	// POINT(2 1)

$polygon = Polygon::fromWKT('POLYGON((0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1))');
$polygon->toWKT();	// POLYGON((0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1))
```

##### From/To String

```php
// fromString($wkt, $srid = 0)
$point = new Point(1, 2);	// lat, lng
(string)$point			// lng, lat: 2 1

$polygon = Polygon::fromString('(0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1)');
(string)$polygon;	// (0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1)
```

##### From/To JSON ([GeoJSON](http://geojson.org/))

The Geometry classes implement [`JsonSerializable`](http://php.net/manual/en/class.jsonserializable.php) and `Illuminate\Contracts\Support\Jsonable` to help serialize into GeoJSON:

```php
$point = new Point(40.7484404, -73.9878441);

json_encode($point); // or $point->toJson();

// {
//   "type": "Feature",
//   "properties": {},
//   "geometry": {
//     "type": "Point",
//     "coordinates": [
//       -73.9878441,
//       40.7484404
//     ]
//   }
// }
```

To deserialize a GeoJSON string into a Geometry class, you can use `Geometry::fromJson($json_string)` :

```php
$location = Geometry::fromJson('{"type":"Point","coordinates":[3.4,1.2]}');
$location instanceof Point::class;  // true
$location->getLat();  // 1.2
$location->getLng(); // 3.4
```

## Scopes: Spatial analysis functions

Spatial analysis functions are implemented using [Eloquent Local Scopes](https://laravel.com/docs/5.4/eloquent#local-scopes).

Available scopes:

- `distance($geometryColumn, $geometry, $distance)`
- `distanceExcludingSelf($geometryColumn, $geometry, $distance)`
- `distanceSphere($geometryColumn, $geometry, $distance)`
- `distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)`
- `comparison($geometryColumn, $geometry, $relationship)`
- `within($geometryColumn, $polygon)`
- `crosses($geometryColumn, $geometry)`
- `contains($geometryColumn, $geometry)`
- `disjoint($geometryColumn, $geometry)`
- `equals($geometryColumn, $geometry)`
- `intersects($geometryColumn, $geometry)`
- `overlaps($geometryColumn, $geometry)`
- `doesTouch($geometryColumn, $geometry)`
- `orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')`
- `orderByDistance($geometryColumn, $geometry, $direction = 'asc')`
- `orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')`

*Note that behavior and availability of MySQL spatial analysis functions differs in each MySQL version (cf. [documentation](https://dev.mysql.com/doc/refman/8.0/en/spatial-function-reference.html)).*

## Contributing

Recommendations and pull request are most welcome! Pull requests with tests are the best! There are still a lot of MySQL spatial functions to implement or creative ways to use spatial functions. 

## Credits

Originally inspired from [njbarrett's Laravel postgis package](https://github.com/njbarrett/laravel-postgis).

Transplanted from [grimzy/laravel-mysql-spatial](https://github.com/grimzy/laravel-mysql-spatial)
