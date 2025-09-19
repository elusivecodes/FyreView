<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Utility\Path;
use Fyre\Utility\Traits\MacroTrait;

use function array_splice;
use function in_array;
use function is_file;
use function preg_replace;
use function str_ends_with;
use function strtolower;

/**
 * TemplateLocator
 */
class TemplateLocator
{
    use MacroTrait;

    public const CELLS_FOLDER = 'cells';

    public const ELEMENTS_FOLDER = 'elements';

    public const LAYOUTS_FOLDER = 'layouts';

    protected const FILE_EXTENSION = '.php';

    protected array $paths = [];

    /**
     * Normalize a file name.
     *
     * @param string $string The input string.
     * @return string The normalized string.
     */
    public static function normalize(string $string): string
    {
        $string = preg_replace('/(?<=[^A-Z])[A-Z]/', '_\0', $string);

        return strtolower($string);
    }

    /**
     * Add a path for loading templates.
     *
     * @param string $path The path.
     * @return static The TemplateLocator.
     */
    public function addPath(string $path): static
    {
        $path = Path::resolve($path);

        if (!in_array($path, $this->paths)) {
            $this->paths[] = $path;
        }

        return $this;
    }

    /**
     * Clear all paths.
     */
    public function clear(): void
    {
        $this->paths = [];
    }

    /**
     * Get the paths.
     *
     * @return array The paths.
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Find a file in paths.
     *
     * @param string $name The file name.
     * @param string $folder The file folder.
     * @return string|null The file path.
     */
    public function locate(string $name, string $folder = ''): string|null
    {
        if (!str_ends_with($name, static::FILE_EXTENSION)) {
            $name .= static::FILE_EXTENSION;
        }

        foreach ($this->paths as $path) {
            $filePath = Path::join($path, $folder, $name);

            if (is_file($filePath)) {
                return $filePath;
            }
        }

        return null;
    }

    /**
     * Remove a path.
     *
     * @param string $path The path to remove.
     * @return static The TemplateLocator.
     */
    public function removePath(string $path): static
    {
        $path = Path::resolve($path);

        foreach ($this->paths as $i => $otherPath) {
            if ($otherPath !== $path) {
                continue;
            }

            array_splice($this->paths, $i, 1);
            break;
        }

        return $this;
    }
}
