<?php

declare(strict_types=1);

namespace Nashgao\HyperfMySQLSpatial;

use Doctrine\DBAL\Types\Type as DoctrineType;
use Hyperf\Database\MySqlConnection as HyperfMySQLConnection;
use Nashgao\HyperfMySQLSpatial\Schema\Builder;
use Nashgao\HyperfMySQLSpatial\Schema\Grammars\MySqlGrammar;

class MySQLConnection extends HyperfMySQLConnection
{
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        if (\class_exists(DoctrineType::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry',
                'point',
                'linestring',
                'polygon',
                'multipoint',
                'multilinestring',
                'multipolygon',
                'geometrycollection',
                'geomcollection',
            ];
            $dbPlatform = $this->getDoctrineSchemaManager()->getDatabasePlatform();
            foreach ($geometries as $type) {
                $dbPlatform->registerDoctrineTypeMapping($type, 'string');
            }
        }
    }

    /**
     * Get a schema builder instance for the connection.
     */
    public function getSchemaBuilder(): Builder
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Builder($this);
    }

    /**
     * Get the default schema grammar instance.
     */
    protected function getDefaultSchemaGrammar(): MySqlGrammar
{
        return $this->withTablePrefix(new MySqlGrammar());
    }
}
