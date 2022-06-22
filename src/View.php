<?php
declare(strict_types=1);

namespace Fyre\View;

use
    Fyre\Utility\Path,
    Fyre\View\Exceptions\ViewException,
    RuntimeException;

use function
    array_key_exists,
    array_merge,
    extract,
    func_get_arg,
    in_array,
    is_file,
    ob_end_clean,
    ob_get_contents,
    ob_start,
    str_ends_with;

/**
 * View
 */
class View
{

    protected const ELEMENTS_FOLDER = 'elements';
    protected const LAYOUTS_FOLDER = 'layouts';
    protected const FILE_EXTENSION = '.php';

    protected static array $paths = [];

    protected array $data = [];

    protected string|null $file = null; 

    protected string|null $layout = 'default';

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
        static::$paths = [];
    }

    /**
     * Load a helper.
     * @param string $name The helper name.
     */
    public function __get(string $name)
    {
        if (!HelperRegistry::find($name)) {
            throw new RuntimeException('Undefined property: '.$name);
        }

        $this->loadHelper($name);

        return $this->$name;
    }

    /**
     * Get the layout content.
     * @return string The layout content.
     */
    public function content(): string
    {
        $file = $this->file ?? '';

        $this->file = null;

        if (!$file) {
            throw ViewException::forInvalidTemplate($file);
        }

        $filePath = static::findFile($file);

        if (!$filePath) {
            throw ViewException::forInvalidTemplate($file);
        }

        return $this->evaluate($filePath, $this->data);
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
     * Get the layout.
     * @return string|null The layout.
     */
    public function getLayout(): string|null
    {
        return $this->layout;
    }

    /**
     * Load a Helper.
     * @param string $name The helper name.
     * @param array $options The helper options.
     * @return View The View.
     */
    public function loadHelper(string $name, array $options = []): static
    {
        $this->$name = HelperRegistry::load($name, $this, $options);

        return $this;
    }

    /**
     * Render a template.
     * @param string $file The template file.
     * @return string The rendered template.
     * @throws ViewException if the template does not exist.
     */
    public function render(string $file): string
    {
        $this->file = $file;

        if (!$this->layout) {
            return $this->content();
        }

        $layoutPath = static::findFile($this->layout, static::LAYOUTS_FOLDER);

        if (!$layoutPath) {
            return $this->content();
        }

        $result = $this->evaluate($layoutPath, $this->data);

        $this->file = null;

        return $result;
    }

    /**
     * Set view data.
     * @param array $data The view data.
     * @return View The View.
     */
    public function setData(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Set the layout.
     * @param string|null $layout The layout.
     * @return View The View.
     */
    public function setLayout(string|null $layout): static
    {
        $this->layout = $layout;

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

}
