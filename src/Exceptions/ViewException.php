<?php
declare(strict_types=1);

namespace Fyre\View\Exceptions;

use RuntimeException;

/**
 * ViewException
 */
class ViewException extends RuntimeException
{
    public static function forInvalidCell(string $name): static
    {
        return new static('Cell not found: '.$name);
    }

    public static function forInvalidCellMethod(string $name, string $method): static
    {
        return new static('Cell method not valid: '.$name.'::'.$method);
    }

    public static function forInvalidElement(string $name): static
    {
        return new static('Element not found: '.$name);
    }

    public static function forInvalidHelper(string $name): static
    {
        return new static('Helper not found: '.$name);
    }

    public static function forInvalidLayout(string $name): static
    {
        return new static('Layout not found: '.$name);
    }

    public static function forInvalidTemplate(string $name): static
    {
        return new static('Template not found: '.$name);
    }

    public static function forUnclosedBlock(): static
    {
        return new static('Unclosed view block.');
    }

    public static function forUnopenedBlock(): static
    {
        return new static('Unopened view block.');
    }
}
