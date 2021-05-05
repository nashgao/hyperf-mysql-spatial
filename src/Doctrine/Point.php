<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Point extends Type
{
    const POINT = 'point';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'point';
    }

    public function getName(): string
    {
        return self::POINT;
    }
}
