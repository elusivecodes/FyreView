<?php
declare(strict_types=1);

namespace Fyre\View;

use
    Fyre\Utility\Path,
    Fyre\View\Exceptions\ViewException;

use function
    array_merge,
    class_exists,
    extract,
    func_get_arg,
    in_array,
    is_file,
    ob_end_clean,
    ob_get_contents,
    ob_start,
    str_ends_with,
    trim;

/**
 * View
 */
class View
{

    protected const ELEMENTS_FOLDER = 'elements';
    protected const FILE_EXTENSION = '.php';

    protected static array $namespaces = [];
    protected static array $paths = [];

    protected array $data = [];

    protected array $helpers = [];

    /**
     * Add a namespace for loading helpers.
     * @param string $namespace The namespace.
     */
    public static function addNamespace(string $namespace): void
    {
        if (!in_array($namespace, static::$namespaces)) {
            static::$namespaces[] = static::normalizeNamespace($namespace);
        }
    }

    /**
     * Add a path for loading templates.
     * @param string $path The path.
     */
    public static function addPath(string $path): void
    {
        $path = Path::resolve($path);

        if (!in_array($path, static::$paths)) {
            static::$paths[] = $path;
        }
    }

    /**
     * Clear all namespaces and paths.
     */
    public static function clear(): void
    {
        static::$namespaces = [];
        static::$paths = [];
    }

    /**
     * Load a helper.
     * @param string $name The helper name.
     */
    public function __get(string $name)
    {
        return $this->helpers[$name] ??= $this->loadHelper($name);
    }

    /**
     * Determine if a helper exists.
     * @param string $name The helper name.
     * @return bool TRUE if the helper exists, otherwise FALSE.
     */
    public function __isset(string $name): bool
    {
        return !!$this->$name;
    }

    /**
     * Render an element.
     * @param string $file The element file.
     * @return string The rendered element.
     * @throws ViewException if the element does not exist.
     */
    public function element(string $file, array $data = []): string
    {
        $filePath = static::findFile($file, static::ELEMENTS_FOLDER);

        if (!$filePath) {
            throw ViewException::forInvalidElement($file);
        }

        return $this->evaluate($filePath, $data);
    }

    /**
     * Get the view data.
     * @return array The view data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Render a template.
     * @param string $file The template file.
     * @return string The rendered template.
     * @throws ViewException if the template does not exist.
     */
    public function render(string $file): string
    {
        $filePath = static::findFile($file);

        if (!$filePath) {
            throw ViewException::forInvalidTemplate($file);
        }

        return $this->evaluate($filePath, $this->data);
    }

    /**
     * Set view data.
     * @param array $data The view data.
     * @return View The View.
     */
    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Render and inject data into a file.
     * @param string $filePath The file path.
     * @param array $data The data to inject.
     * @return string The rendered file.
     */
    protected function evaluate(string $filePath, array $data): string
    {
        extract($data);

        try {
            ob_start();

            include func_get_arg(0);

            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }

    /**
     * Load a helper.
     * @param string $name The helper name.
     * @return Helper The Helper.
     * @throws ViewException if the helper does not exist.
     */
    protected function loadHelper(string $name): Helper
    {
        foreach (static::$namespaces AS $namespace) {
            $className = $namespace.$name;

            if (class_exists($className)) {
                return new $className($this);
            }
        }

        throw ViewException::forInvalidHelper($name);
    }

    /**
     * Find a file in paths.
     * @param string $name The file name.
     * @param string $folder The file folder.
     * @return string|null The file path.
     */
    protected static function findFile(string $name, string $folder = ''): string|null
    {
        if (!str_ends_with($name, static::FILE_EXTENSION)) {
            $name .= static::FILE_EXTENSION;
        }

        foreach (static::$paths AS $path) {
            $filePath = Path::join($path, $folder, $name);

            if (is_file($filePath)) {
                return $filePath;
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
