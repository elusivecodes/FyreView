<?php
declare(strict_types=1);

namespace Fyre\View\Form\Traits;

use Fyre\DB\Types\BinaryType;
use Fyre\DB\Types\BooleanType;
use Fyre\DB\Types\DateTimeType;
use Fyre\DB\Types\DateType;
use Fyre\DB\Types\DecimalType;
use Fyre\DB\Types\EnumType;
use Fyre\DB\Types\FloatType;
use Fyre\DB\Types\IntegerType;
use Fyre\DB\Types\SetType;
use Fyre\DB\Types\StringType;
use Fyre\DB\Types\TextType;
use Fyre\DB\Types\TimeType;
use Fyre\Schema\TableSchema;

use function array_combine;
use function array_key_exists;
use function max;
use function min;
use function pow;

/**
 * SchemaTrait
 */
trait SchemaTrait
{
    protected const MAX_VALUES = [
        'tinyint' => 127,
        'smallint' => 32767,
        'mediumint' => 8388607,
        'int' => 2147483647,
        'integer' => 2147483647,
    ];

    /**
     * Get the default value.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return mixed The default value.
     */
    public static function getSchemaDefaultValue(TableSchema $schema, string $field): mixed
    {
        $type = $schema->getType($field);

        if (!$type) {
            return null;
        }

        $value = $schema->defaultValue($field);

        return $type->parse($value);
    }

    /**
     * Get the maximum value.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return float|null The maximum value.
     */
    public static function getSchemaMax(TableSchema $schema, string $field): float|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        $type = $schema->getType($field);

        if ($type instanceof FloatType) {
            return null;
        }

        $type = $column['type'];
        $length = $column['length'] ?? null;
        $unsigned = $column['unsigned'] ?? false;

        $max = static::MAX_VALUES[$type] ?? null;

        if ($unsigned && $max) {
            $max = ($max * 2) + 1;
        }

        if (!$length) {
            return $max;
        }

        $lengthMax = pow(10, $length) - 1;
        $max = $max ? min($max, $lengthMax) : $lengthMax;

        if ($max > PHP_INT_MAX) {
            return null;
        }

        return $max;
    }

    /**
     * Get the maximum length.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return int|null The maximum length.
     */
    public static function getSchemaMaxLength(TableSchema $schema, string $field): int|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        $type = $schema->getType($field);

        if ($type instanceof StringType && $column['length'] < 524288) {
            return $column['length'];
        }

        return null;
    }

    /**
     * Get the minimum value.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return float|null The minimum value.
     */
    public static function getSchemaMin(TableSchema $schema, string $field): int|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        if (array_key_exists('unsigned', $column) && $column['unsigned']) {
            return 0;
        }

        $type = $schema->getType($field);

        if ($type instanceof FloatType) {
            return null;
        }

        $type = $column['type'];
        $length = $column['length'] ?? null;

        if (array_key_exists($type, static::MAX_VALUES)) {
            $min = (static::MAX_VALUES[$type] + 1) * -1;
        } else {
            $min = null;
        }

        if (!$length) {
            return $min;
        }

        $lengthMax = pow(10, $length) - 1;
        $min = $min ? max($min, -$lengthMax) : -$lengthMax;

        if ($min < PHP_INT_MIN) {
            return null;
        }

        return $min;
    }

    /**
     * Get the option values.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return array|null The options value.
     */
    public static function getSchemaOptionValues(TableSchema $schema, string $field): array|null
    {
        $type = $schema->getType($field);

        if (!$type) {
            return null;
        }

        if ($type instanceof EnumType || $type instanceof SetType) {
            $column = $schema->column($field);

            $values = $column['values'] ?? [];

            return array_combine($values, $values);
        }

        return null;
    }

    /**
     * Get the step interval.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return float|null The step interval.
     */
    public static function getSchemaStep(TableSchema $schema, string $field): float|string|null
    {
        $column = $schema->column($field);

        if (!$column) {
            return null;
        }

        $type = $schema->getType($field);

        if ($type instanceof IntegerType) {
            return 1;
        }

        if ($type instanceof FloatType) {
            return 'any';
        }

        if ($type instanceof DecimalType) {
            if ($column['precision'] > 0) {
                return 1 / pow(10, $column['precision']);
            }

            if ($column['precision'] === 0) {
                return 1;
            }
        }

        return null;
    }

    /**
     * Get the field type.
     *
     * @param TableSchema $schema The TableSchema.
     * @param string $field The field name.
     * @return string The field type.
     */
    public static function getSchemaType(TableSchema $schema, string $field): string
    {
        $column = $schema->column($field);

        if (!$column) {
            return 'text';
        }

        $type = $schema->getType($field);

        if ($type instanceof BooleanType) {
            return 'checkbox';
        }

        if ($type instanceof DateType) {
            return 'date';
        }

        if ($type instanceof TimeType) {
            return 'time';
        }

        if ($type instanceof DateTimeType) {
            return 'datetime';
        }

        if ($type instanceof DecimalType || $type instanceof FloatType || $type instanceof IntegerType) {
            return 'number';
        }

        if ($type instanceof TextType) {
            return 'textarea';
        }

        if ($type instanceof EnumType) {
            return 'select';
        }

        if ($type instanceof SetType) {
            return 'selectMulti';
        }

        if ($type instanceof BinaryType) {
            return 'file';
        }

        return 'text';
    }
}
