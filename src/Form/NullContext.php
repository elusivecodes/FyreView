<?php
declare(strict_types=1);

namespace Fyre\View\Form;

class NullContext extends Context
{

    /**
     * Get the value of a field.
     * @param string $key The field key.
     * @return mixed The value.
     */
    public function getValue(string $key): mixed
    {
        return null;
    }

}
