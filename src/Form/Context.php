<?php
declare(strict_types=1);

namespace Fyre\View\Form;

abstract class Context
{

    /**
     * Get the default value of a field.
     * @param string $key The field key.
     * @return mixed The default value.
     */
    public function getDefaultValue(string $key): mixed
    {
        return null;
    }

    /**
     * Get the maximum value.
     * @param string $key The field key.
     * @return float|null The maximum value.
     */
    public function getMax(string $key): float|null
    {
        return null;
    }

    /**
     * Get the maximum length.
     * @param string $key The field key.
     * @return int|null The maximum length.
     */
    public function getMaxLength(string $key): int|null
    {
        return null;
    }

    /**
     * Get the minimum value.
     * @param string $key The field key.
     * @return float|null The minimum value.
     */
    public function getMin(string $key): float|null
    {
        return null;
    }

    /**
     * Get the step interval.
     * @param string $key The field key.
     * @return string|float|null The step interval.
     */
    public function getStep(string $key): string|float|null
    {
        return null;
    }

    /**
     * Get the field type.
     * @param string $key The field key.
     * @return string The field type.
     */
    public function getType(string $key): string
    {
        return 'text';
    }

    /**
     * Get the value of a field.
     * @param string $key The field key.
     * @return mixed The value.
     */
    abstract public function getValue(string $key): mixed;

    /**
     * Determine if the field is required.
     * @param string $key The field key.
     * @return bool TRUE if the field is required, otherwise FALSE.
     */
    public function isRequired(string $key): bool
    {
        return false;
    }

}
