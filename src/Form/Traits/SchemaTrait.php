<?php
declare(strict_types=1);

namespace Fyre\View\Form\Traits;

use
    Fyre\Schema\TableSchemaInterface;

use const
    PHP_INT_MAX,
    PHP_INT_MIN;

use function
    is_numeric,
    min,
    pow,
    preg_match;

/**
 * SchemaTrait
 */
trait SchemaTrait
{

    /**
     * Get the default value.
     * @param TableSchemaInterface $schema The TableSchema.
     * @param string $field The field name.
     * @return mixed The default value.
     */
    public static function getSchemaDefaultValue(TableSchemaInterface $schema, string $field): mixed
    {
        $column = $schema->column($field);

        if (!$column || $column['default'] === null || $column['default'] === 'NULL') {
            return null;
        }

        if (is_numeric($column['default'])) {
            return (float) $column['default'];
        }

        if (preg_match('/^(["\'])(.*)\1$/', $column['default'], $match)) {
            return $match[2];
        }

        return null;
    }

    /**
     * Get the maximum value.
     * @param TableSchemaInterface $schema The TableSchema.
     * @param string $field The field name.
     * @return float|null The maximum value.
     */
    public static function getSchemaMax(TableSchemaInterface $schema, string $field): float|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        switch ($column['type']) {
            case 'tinyint':
                $max = 127;
                break;
            case 'smallint':
                $max = 32767;
                break;
            case 'mediumint':
                $max = 8388607;
                break;
            case 'int':
                $max = 2147483647;
                break;
            // case 'bigint':
            //     $max = 9223372036854775807;
            //     break;
            default:
                $max = null;
                break;
        }

        if ($column['unsigned'] && $max) {
            $max *= 2;
            $max += 1;
        }

        $lengthMax = pow(10, $column['length']) - 1;
        $max ??= $lengthMax;

        $max = min($max, $lengthMax);

        if ($max > PHP_INT_MAX) {
            return null;
        }

        return $max;
    }

    /**
     * Get the maximum length.
     * @param TableSchemaInterface $schema The TableSchema.
     * @param string $field The field name.
     * @return int|null The maximum length.
     */
    public static function getSchemaMaxLength(TableSchemaInterface $schema, string $field): int|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        switch ($column['type']) {
            case 'binary':
            case 'char':
                return 1;
            case 'varbinary':
            case 'varchar':
                return $column['length'];
            case 'tinyblob':
            case 'tinytext':
                return 255;
            case 'blob':
            case 'text':
                return 65535;
            // case 'mediumblob':
            // case 'mediumtext':
            //     return 16777215;
            // case 'longblob':
            // case 'longtext':
            //     return 4294967295;
            default:
                return null;
        }
    }

    /**
     * Get the minimum value.
     * @param TableSchemaInterface $schema The TableSchema.
     * @param string $field The field name.
     * @return float|null The minimum value.
     */
    public static function getSchemaMin(TableSchemaInterface $schema, string $field): float|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        if ($column['unsigned']) {
            return 0;
        }

        switch ($column['type']) {
            case 'tinyint':
                $min = -128;
                break;
            case 'smallint':
                $min = -32768;
                break;
            case 'mediumint':
                $min = -8388608;
                break;
            case 'int':
                $min = -2147483648;
                break;
            // case 'bigint':
            //     $min = -9223372036854775808;
            //     break;
            default:
                $min = null;
                break;
        }

        $lengthMax = pow(10, $column['length']) - 1;
        $min ??= -$lengthMax;

        $min = max($min, -$lengthMax);

        if ($min < PHP_INT_MIN) {
            return null;
        }

        return $min;
    }

    /**
     * Get the step interval.
     * @param TableSchemaInterface $schema The TableSchema.
     * @param string $field The field name.
     * @return float|null The step interval.
     */
    public static function getSchemaStep(TableSchemaInterface $schema, string $field): string|float|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        switch ($column['type']) {
            case 'bigint':
            case 'int':
            case 'mediumint':
            case 'smallint':
            case 'tinyint':
                return 1;
            case 'decimal':
                return 1 / pow(10, $column['precision']);
            case 'double':
            case 'float':
                return 'any';
            default:
                return null;
        }
    }

    /**
     * Get the field type.
     * @param TableSchemaInterface $schema The TableSchema.
     * @param string $field The field name.
     * @return string The field type.
     */
    public static function getSchemaType(TableSchemaInterface $schema, string $field): string
    {
        $column = $schema->column($field);

        if (!$column) {
            return 'text';
        }

        if ($column['type'] === 'tinyint' && $column['length'] == 1) {
            return 'checkbox';
        }

        switch ($column['type']) {
            case 'boolean':
                return 'checkbox';
            case 'date':
                return 'date';
            case 'datetime':
                return 'datetime';
            case 'time':
                return 'time';
            case 'enum':
            case 'set':
                return 'select';
            case 'blob':
            case 'longblob':
            case 'longtext':
            case 'mediumblob':
            case 'mediumtext':
            case 'text':
            case 'tinyblob':
            case 'tinytext':
                return 'textarea';
            case 'bigint':
            case 'decimal':
            case 'double':
            case 'float':
            case 'int':
            case 'mediumint':
            case 'smallint':
            case 'tinyint':
                return 'number';
            default:
                return 'text';
        }
    }

}
