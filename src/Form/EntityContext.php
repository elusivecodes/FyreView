<?php
declare(strict_types=1);

namespace Fyre\View\Form;

use Closure;
use Fyre\Entity\Entity;
use Fyre\ORM\Model;
use Fyre\ORM\ModelRegistry;
use Fyre\ORM\Relationship;
use Fyre\View\Form\Traits\SchemaTrait;
use Fyre\View\Form\Traits\ValidationTrait;

use function array_key_exists;
use function array_pop;
use function array_shift;
use function explode;
use function in_array;
use function is_array;
use function max;
use function min;

/**
 * EntityContext
 */
class EntityContext extends Context
{
    use SchemaTrait;
    use ValidationTrait;

    protected Model $model;

    protected array $models = [];

    /**
     * New EntityContext constructor.
     *
     * @param Entity $item The entity.
     */
    public function __construct(
        ModelRegistry $modelRegistry,
        protected Entity $item
    ) {
        $this->model = $modelRegistry->use($item->getSource());
    }

    /**
     * Get the default value of a field.
     *
     * @param string $key The field key.
     * @return mixed The default value.
     */
    public function getDefaultValue(string $key): mixed
    {
        if (!$this->item->isNew()) {
            return null;
        }

        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $schema = $model->getSchema();

        return $this->getSchemaDefaultValue($schema, $field);
    }

    /**
     * Get the maximum value.
     *
     * @param string $key The field key.
     * @return float|null The maximum value.
     */
    public function getMax(string $key): float|null
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $validator = $model->getValidator();
        $schema = $model->getSchema();

        $validatorMax = static::getValidationMax($validator, $field);
        $schemaMax = static::getSchemaMax($schema, $field);

        if ($validatorMax !== null && $schemaMax !== null) {
            return min($validatorMax, $schemaMax);
        }

        return $validatorMax ?? $schemaMax;
    }

    /**
     * Get the maximum length.
     *
     * @param string $key The field key.
     * @return int|null The maximum length.
     */
    public function getMaxLength(string $key): int|null
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $validator = $model->getValidator();
        $schema = $model->getSchema();

        $validatorMaxLength = static::getValidationMaxLength($validator, $field);
        $schemaMaxLength = static::getSchemaMaxLength($schema, $field);

        if ($validatorMaxLength !== null && $schemaMaxLength !== null) {
            return min($validatorMaxLength, $schemaMaxLength);
        }

        return $validatorMaxLength ?? $schemaMaxLength;
    }

    /**
     * Get the minimum value.
     *
     * @param string $key The field key.
     * @return float|null The minimum value.
     */
    public function getMin(string $key): float|null
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $validator = $model->getValidator();
        $schema = $model->getSchema();

        $validatorMin = static::getValidationMin($validator, $field);
        $schemaMin = static::getSchemaMin($schema, $field);

        if ($validatorMin !== null && $schemaMin !== null) {
            return max($validatorMin, $schemaMin);
        }

        return $validatorMin ?? $schemaMin;
    }

    /**
     * Get the option values for a field.
     *
     * @param string $key The field key.
     * @return array|null The option values.
     */
    public function getOptionValues(string $key): array|null
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $schema = $model->getSchema();

        return static::getSchemaOptionValues($schema, $field);
    }

    /**
     * Get the step interval.
     *
     * @param string $key The field key.
     * @return float|string|null The step interval.
     */
    public function getStep(string $key): float|string|null
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $schema = $model->getSchema();

        return static::getSchemaStep($schema, $field);
    }

    /**
     * Get the field type.
     *
     * @param string $key The field key.
     * @return string The field type.
     */
    public function getType(string $key): string
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return parent::getType($key);
        }

        $relationship = static::findRelationship(
            $model,
            fn(Relationship $relationship): bool => !$relationship->isOwningSide() && $relationship->getForeignKey() === $field
        );

        if ($relationship) {
            return 'select';
        }

        $schema = $model->getSchema();

        if (in_array($field, $schema->primaryKey())) {
            return 'hidden';
        }

        return static::getSchemaType($schema, $field);
    }

    /**
     * Get the value of a field.
     *
     * @param string $key The field key.
     * @return mixed The value.
     */
    public function getValue(string $key): mixed
    {
        $parts = explode('.', $key);

        $value = $this->item;

        foreach ($parts as $part) {
            if ($value instanceof Entity || is_array($value)) {
                $value = $value[$part] ?? null;
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * Determine if the field is required.
     *
     * @param string $key The field key.
     * @return bool TRUE if the field is required, otherwise FALSE.
     */
    public function isRequired(string $key): bool
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return parent::isRequired($key);
        }

        $validator = $model->getValidator();

        return static::isValidationRequired($validator, $field);
    }

    /**
     * Get the Model/field for a field.
     *
     * @param string $key The field key.
     * @return array The Model/field.
     */
    protected function getModelField(string $key): array
    {
        if (array_key_exists($key, $this->models)) {
            return $this->models[$key];
        }

        $parts = explode('.', $key);

        $field = array_pop($parts);

        $model = $this->model;

        while ($parts !== []) {
            $part = array_shift($parts);

            $relationship = static::findRelationship(
                $model,
                fn(Relationship $relationship): bool => $relationship->getProperty() === $part
            );

            $model = $relationship->getTarget();

            if ($relationship->hasMultiple()) {
                array_shift($parts);
            }
        }

        return $models[$key] = [$model, $field];
    }

    /**
     * Find a relationship that passes a callback.
     *
     * @param Model $model The Model.
     * @param Closure $callback The callback test.
     * @return Relationship|null The Relationship.
     */
    protected static function findRelationship(Model $model, Closure $callback): Relationship|null
    {
        $relationships = $model->getRelationships();

        foreach ($relationships as $relationship) {
            if (!$callback($relationship)) {
                continue;
            }

            return $relationship;
        }

        return null;
    }
}
