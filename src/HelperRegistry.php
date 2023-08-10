<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\View\Exceptions\ViewException;

use function array_splice;
use function class_exists;
use function in_array;
use function is_subclass_of;
use function trim;

abstract class HelperRegistry
{

    protected static array $namespaces = [];

    protected static array $helpers = [];

    /**
     * Add a namespace for loading helpers.
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
     * Clear all namespaces and helpers.
     */
    public static function clear(): void
    {
        static::$namespaces = [];
        static::$helpers = [];
    }

    /**
     * Find a helper class.
     * @param string $name The helper name.
     * @return string|null The helper class.
     */
    public static function find(string $name): string|null
    {
        return static::$helpers[$name] ??= static::locate($name);
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
     * Load a helper.
     * @param string $name The helper name.
     * @param View $view The View.
     * @param array $options The helper options.
     * @return Helper The Helper.
     * @throws ViewException if the helper is not valid.
     */
    public static function load(string $name, View $view, array $options = []): Helper
    {
        $className = static::find($name);

        if (!$className) {
            throw ViewException::forInvalidHelper($name);
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
     * Locate a helper class.
     * @param string $name The helper name.
     * @return string|null The helper class.
     */
    protected static function locate(string $name): string|null
    {
        $namespaces = array_merge(static::$namespaces, ['\Fyre\View\Helpers\\']);

        foreach ($namespaces AS $namespace) {
            $className = $namespace.$name.'Helper';

            if (class_exists($className) && is_subclass_of($className, Helper::class)) {
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
