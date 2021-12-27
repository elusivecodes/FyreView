<?php
declare(strict_types=1);

namespace Fyre\View\Exceptions;

use
    RuntimeException;

/**
 * ViewException
 */
class ViewException extends RuntimeException
{

    public static function forInvalidElement(string $name): self
    {
        return new static('Element not found: '.$name);
    }

    public static function forInvalidHelper(string $name): self
    {
        return new static('Helper not found: '.$name);
    }

    public static function forInvalidTemplate(string $name): self
    {
        return new static('Template not found: '.$name);
    }

}
