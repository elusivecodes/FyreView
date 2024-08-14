<?php
declare(strict_types=1);

namespace Fyre\View\Form;

class NullContext extends Context
{
    /**
     * Get the options for a field.
     *
     * @param string $key The field key.
     * @return array|null The option values.
     */
    public function getOptionValues(string $key): array|null
    {
        return null;
    }

    /**
     * Get the value of a field.
     *
     * @param string $key The field key.
     * @return mixed The value.
     */
    public function getValue(string $key): mixed
    {
        return null;
    }
}
