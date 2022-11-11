<?php
declare(strict_types=1);

namespace Fyre\View\Form;

use
    Closure,
    Fyre\Entity\Entity,
    Fyre\View\Form\Traits\SchemaTrait,
    Fyre\View\Form\Traits\ValidationTrait,
    Fyre\ORM\Relationships\Relationship,
    Fyre\ORM\Model,
    Fyre\ORM\ModelRegistry;

use function
    array_filter,
    array_key_exists,
    array_pop,
    array_shift,
    explode,
    is_array,
    max,
    min;

/**
 * EntityContext
 */
class EntityContext extends Context
{

    protected Entity $item;

    protected Model $model;

    protected array $models = [];

    use
        SchemaTrait,
        ValidationTrait;

    /**
     * New EntityContext constructor.
     * @param Entity $item The entity.
     */
    public function __construct(Entity $item)
    {
        $this->item = $item;

        $source = $item->getSource();
        $this->model = ModelRegistry::use($source);
    }

    /**
     * Get the default value of a field.
     * @param string $key The field key.
     * @return mixed The default value.
     */
    public function getDefaultValue(string $key): mixed
    {
        [$model, $field] = $this->getModelField($key);

        if (!$model) {
            return null;
        }

        $schema = $model->getSchema();

        return $this->getSchemaDefaultValue($schema, $field);
    }

    /**
     * Get the maximum value.
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

        $values = array_filter(
            [$validatorMax, $schemaMax],
            fn(float|null $value): bool => $value !== null
        );

        if ($values === []) {
            return null;
        }

        return min($values);
    }

    /**
     * Get the maximum length.
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

        $values = array_filter(
            [$validatorMaxLength, $schemaMaxLength],
            fn(int|null $value): bool => $value !== null
        );

        if ($values === []) {
            return null;
        }

        return min($values);
    }

    /**
     * Get the minimum value.
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

        $values = array_filter(
            [$validatorMin, $schemaMin],
            fn(float|null $value): bool => $value !== null
        );

        if ($values === []) {
            return null;
        }

        return max($values);
    }

    /**
     * Get the step interval.
     * @param string $key The field key.
     * @return string|float|null The step interval.
     */
    public function getStep(string $key): string|float|null
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
            fn(Relationship $relationship): bool =>
                !$relationship->isOwningSide() && $relationship->getForeignKey() === $field
        );

        if ($relationship) {
            return 'select';
        }

        $schema = $model->getSchema();

        return static::getSchemaType($schema, $field);
    }

    /**
     * Get the value of a field.
     * @param string $key The field key.
     * @return mixed The value.
     */
    public function getValue(string $key): mixed
    {
        $parts = explode('.', $key);

        $value = $this->item;

        foreach ($parts AS $part) {
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
                fn(Relationship $relationship): bool =>
                    $relationship->getProperty() === $part
            );

            $model = $relationship->getTarget();

            if ($relationship->hasMultiple()) {
                array_shift($parts);
            }
        }

        return $this->models[$key] = [$model, $field];
    }

    /**
     * Find a relationship that passes a callback.
     * @param Model $model The Model.
     * @param Closure $callback The callback test.
     * @return Relationship|null The Relationship.
     */
    protected static function findRelationship(Model $model, Closure $callback): Relationship|null
    {
        $relationships = $model->getRelationships();

        foreach ($relationships AS $relationship) {
            if (!$callback($relationship)) {
                continue;
            }

            return $relationship;
        }

        return null;
    }

}
