<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Container\Container;
use Fyre\View\Exceptions\ViewException;

use function array_merge;
use function array_splice;
use function class_exists;
use function in_array;
use function is_subclass_of;
use function trim;

/**
 * HelperRegistry
 */
class HelperRegistry
{
    protected array $helpers = [];

    protected array $namespaces = [];

    /**
     * New HelperRegistry constructor.
     *
     * @param Container $container The Container.
     */
    public function __construct(
        protected Container $container
    ) {}

    /**
     * Add a namespace for loading helpers.
     *
     * @param string $namespace The namespace.
     * @return static The HelperRegistry.
     */
    public function addNamespace(string $namespace): static
    {
        $namespace = static::normalizeNamespace($namespace);

        if (!in_array($namespace, $this->namespaces)) {
            $this->namespaces[] = $namespace;
        }

        return $this;
    }

    /**
     * Build a helper.
     *
     * @param string $name The helper name.
     * @param View $view The View.
     * @param array $options The helper options.
     * @return Helper The Helper.
     *
     * @throws ViewException if the helper is not valid.
     */
    public function build(string $name, View $view, array $options = []): Helper
    {
        $className = $this->find($name);

        if (!$className) {
            throw ViewException::forInvalidHelper($name);
        }

        return $this->container->build($className, ['view' => $view, 'options' => $options]);
    }

    /**
     * Clear all namespaces and helpers.
     */
    public function clear(): void
    {
        $this->namespaces = [];
        $this->helpers = [];
    }

    /**
     * Find a helper class.
     *
     * @param string $name The helper name.
     * @return string|null The helper class.
     */
    public function find(string $name): string|null
    {
        return $this->helpers[$name] ??= $this->locate($name);
    }

    /**
     * Get the namespaces.
     *
     * @return array The namespaces.
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * Determine if a namespace exists.
     *
     * @param string $namespace The namespace.
     * @return bool TRUE if the namespace exists, otherwise FALSE.
     */
    public function hasNamespace(string $namespace): bool
    {
        $namespace = static::normalizeNamespace($namespace);

        return in_array($namespace, $this->namespaces);
    }

    /**
     * Remove a namespace.
     *
     * @param string $namespace The namespace.
     * @return static The HelperRegistry.
     */
    public function removeNamespace(string $namespace): static
    {
        $namespace = static::normalizeNamespace($namespace);

        foreach ($this->namespaces as $i => $otherNamespace) {
            if ($otherNamespace !== $namespace) {
                continue;
            }

            array_splice($this->namespaces, $i, 1);
            break;
        }

        return $this;
    }

    /**
     * Locate a helper class.
     *
     * @param string $name The helper name.
     * @return string|null The helper class.
     */
    protected function locate(string $name): string|null
    {
        $namespaces = array_merge($this->namespaces, ['\Fyre\View\Helpers\\']);

        foreach ($namespaces as $namespace) {
            $className = $namespace.$name.'Helper';

            if (class_exists($className) && is_subclass_of($className, Helper::class)) {
                return $className;
            }
        }

        return null;
    }

    /**
     * Normalize a namespace
     *
     * @param string $namespace The namespace.
     * @return string The normalized namespace.
     */
    protected static function normalizeNamespace(string $namespace): string
    {
        return trim($namespace, '\\').'\\';
    }
}
