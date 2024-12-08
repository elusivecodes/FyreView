<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Container\Container;
use Fyre\View\Exceptions\ViewException;

use function array_splice;
use function class_exists;
use function in_array;
use function is_subclass_of;
use function trim;

/**
 * CellRegistry
 */
class CellRegistry
{
    protected array $cells = [];

    protected Container $container;

    protected array $namespaces = [];

    /**
     * New CellRegistry constructor.
     *
     * @param Container $container The Container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add a namespace for loading cells.
     *
     * @param string $namespace The namespace.
     * @return static The CellRegistry.
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
     * Build a cell.
     *
     * @param string $name The cell name.
     * @param View $view The View.
     * @param array $options The cell options.
     * @return Cell The Cell.
     *
     * @throws ViewException if the cell is not valid.
     */
    public function build(string $name, View $view, array $options = []): Cell
    {
        $className = $this->find($name);

        if (!$className) {
            throw ViewException::forInvalidCell($name);
        }

        return $this->container->build($className, ['view' => $view, 'options' => $options]);
    }

    /**
     * Clear all namespaces and cells.
     */
    public function clear(): void
    {
        $this->namespaces = [];
        $this->cells = [];
    }

    /**
     * Find a cell class.
     *
     * @param string $name The cell name.
     * @return string|null The cell class.
     */
    public function find(string $name): string|null
    {
        return $this->cells[$name] ??= $this->locate($name);
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
     * @return static The CellRegistry.
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
     * Locate a cell class.
     *
     * @param string $name The cell name.
     * @return string|null The cell class.
     */
    protected function locate(string $name): string|null
    {
        $namespaces = array_merge($this->namespaces, ['\Fyre\View\Cells\\']);

        foreach ($namespaces as $namespace) {
            $className = $namespace.$name.'Cell';

            if (class_exists($className) && is_subclass_of($className, Cell::class)) {
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
