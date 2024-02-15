<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\View\Exceptions\ViewException;

use function array_splice;
use function class_exists;
use function in_array;
use function is_subclass_of;
use function trim;

/**
 * CellRegistry
 */
abstract class CellRegistry
{

    protected static array $namespaces = [];

    protected static array $cells = [];

    /**
     * Add a namespace for loading cells.
     * @param string $namespace The namespace.
     */
    public static function addNamespace(string $namespace): void
    {
        $namespace = static::normalizeNamespace($namespace);

        if (!in_array($namespace, static::$namespaces)) {
            static::$namespaces[] = $namespace;
        }
    }

    /**
     * Clear all namespaces and cells.
     */
    public static function clear(): void
    {
        static::$namespaces = [];
        static::$cells = [];
    }

    /**
     * Find a cell class.
     * @param string $name The cell name.
     * @return string|null The cell class.
     */
    public static function find(string $name): string|null
    {
        return static::$cells[$name] ??= static::locate($name);
    }

    /**
     * Get the namespaces.
     * @return array The namespaces.
     */
    public static function getNamespaces(): array
    {
        return static::$namespaces;
    }

    /**
     * Determine if a namespace exists.
     * @param string $namespace The namespace.
     * @return bool TRUE if the namespace exists, otherwise FALSE.
     */
    public static function hasNamespace(string $namespace): bool
    {
        $namespace = static::normalizeNamespace($namespace);

        return in_array($namespace, static::$namespaces);
    }

    /**
     * Load a cell.
     * @param string $name The cell name.
     * @param View $view The View.
     * @param array $options The cell options.
     * @return Cell The Cell.
     * @throws ViewException if the cell is not valid.
     */
    public static function load(string $name, View $view, array $options = []): Cell
    {
        $className = static::find($name);

        if (!$className) {
            throw ViewException::forInvalidCell($name);
        }

        return new $className($view, $options);
    }

    /**
     * Remove a namespace.
     * @param string $namespace The namespace.
     * @return bool TRUE If the namespace was removed, otherwise FALSE.
     */
    public static function removeNamespace(string $namespace): bool
    {
        $namespace = static::normalizeNamespace($namespace);

        foreach (static::$namespaces AS $i => $otherNamespace) {
            if ($otherNamespace !== $namespace) {
                continue;
            }

            array_splice(static::$namespaces, $i, 1);

            return true;
        }

        return false;
    }

    /**
     * Locate a cell class.
     * @param string $name The cell name.
     * @return string|null The cell class.
     */
    protected static function locate(string $name): string|null
    {
        $namespaces = array_merge(static::$namespaces, ['\Fyre\View\Cells\\']);

        foreach ($namespaces AS $namespace) {
            $className = $namespace.$name.'Cell';

            if (class_exists($className) && is_subclass_of($className, Cell::class)) {
                return $className;
            }
        }

        return null;
    }

    /**
     * Normalize a namespace
     * @param string $namespace The namespace.
     * @return string The normalized namespace.
     */
    protected static function normalizeNamespace(string $namespace): string
    {
        $namespace = trim($namespace, '\\');

        return $namespace ?
            '\\'.$namespace.'\\' :
            '\\';
    }

}
