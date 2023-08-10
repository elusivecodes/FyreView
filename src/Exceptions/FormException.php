<?php
declare(strict_types=1);

namespace Fyre\View\Exceptions;

use RuntimeException;

/**
 * FormException
 */
class FormException extends RuntimeException
{

    public static function forInvalidContext(): static
    {
        return new static('Invalid form context.');
    }

    public static function forInvalidInputType(string $type): static
    {
        return new static('Invalid input type: '.$type);
    }

    public static function forUnclosedForm(): static
    {
        return new static('Unclosed form.');
    }

}
