<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LineString extends Type
{
    const LINESTRING = 'linestring';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'linestring';
    }

    public function getName(): string
    {
        return self::LINESTRING;
    }
}
