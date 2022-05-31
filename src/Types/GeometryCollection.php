<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Types;

use ArrayAccess;
use ArrayIterator;
use Countable;
use GeoJson\Feature\FeatureCollection;
use GeoJson\GeoJson;
use Hyperf\Utils\Contracts\Arrayable;
use InvalidArgumentException;
use IteratorAggregate;
use Nashgao\HyperfMySQLSpatial\Exceptions\InvalidGeoJsonException;

class GeometryCollection extends Geometry implements IteratorAggregate, ArrayAccess, Arrayable, Countable
{
    /**
     * The minimum number of items required to create this collection.
     */
    protected int $minimumCollectionItems = 0;

    /**
     * The class of the items in the collection.
     */
    protected string $collectionItemType = GeometryInterface::class;

    /**
     * The items contained in the spatial collection.
     *
     * @var GeometryInterface[]
     */
    protected array $items = [];

    /**
     * @param GeometryInterface[] $geometries
     * @param int $srid
     *
     * @throws InvalidArgumentException
     */
    final public function __construct(array $geometries, int $srid = 0)
    {
        parent::__construct($srid);

        $this->validateItems($geometries);

        $this->items = $geometries;
    }

    public function __toString(): string
    {
        return implode(',', array_map(function (GeometryInterface $geometry) {
            return $geometry->toWKT();
        }, $this->items));
    }

    public function getGeometries(): array
    {
        return $this->items;
    }

    public function toWKT(): string
    {
        return sprintf('GEOMETRYCOLLECTION(%s)', (string) $this);
    }

    public static function fromString($wktArgument, $srid = 0): self
    {
        if (empty($wktArgument)) {
            return new static([]);
        }

        $geometry_strings = preg_split('/,\s*(?=[A-Za-z])/', $wktArgument);

        return new static(array_map(function ($geometry_string) {
            $klass = Geometry::getWKTClass($geometry_string);

            return call_user_func($klass . '::fromWKT', $geometry_string);
        }, $geometry_strings), $srid);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): ?GeometryInterface
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->validateItemType($value);

        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public static function fromJson($geoJson): self
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson));
        }

        if (! is_a($geoJson, FeatureCollection::class)) {
            throw new InvalidGeoJsonException('Expected ' . FeatureCollection::class . ', got ' . get_class($geoJson));
        }

        $set = [];
        foreach ($geoJson->getFeatures() as $feature) {
            $set[] = parent::fromJson($feature);
        }

        return new self($set);
    }

    /**
     * Convert to GeoJson GeometryCollection that is jsonable to GeoJSON.
     *
     * @return \GeoJson\Geometry\GeometryCollection
     */
    public function jsonSerialize(): \GeoJson\Geometry\Geometry
    {
        $geometries = [];
        foreach ($this->items as $geometry) {
            $geometries[] = $geometry->jsonSerialize();
        }

        return new \GeoJson\Geometry\GeometryCollection($geometries);
    }

    /**
     * Checks whether the items are valid to create this collection.
     */
    protected function validateItems(array $items)
    {
        $this->validateItemCount($items);

        foreach ($items as $item) {
            $this->validateItemType($item);
        }
    }

    /**
     * Checks whether the array has enough items to generate a valid WKT.
     *
     * @see $minimumCollectionItems
     */
    protected function validateItemCount(array $items): void
    {
        if (count($items) < $this->minimumCollectionItems) {
            $entries = $this->minimumCollectionItems === 1 ? 'entry' : 'entries';

            throw new InvalidArgumentException(sprintf(
                '%s must contain at least %d %s',
                get_class($this),
                $this->minimumCollectionItems,
                $entries
            ));
        }
    }

    /**
     * Checks the type of the items in the array.
     *
     * @param $item
     *
     * @see $collectionItemType
     */
    protected function validateItemType($item): void
    {
        if (! $item instanceof $this->collectionItemType) {
            throw new InvalidArgumentException(sprintf(
                '%s must be a collection of %s',
                get_class($this),
                $this->collectionItemType
            ));
        }
    }
}
