<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Utility\Path;

use function array_splice;
use function in_array;
use function is_file;
use function preg_replace;
use function str_ends_with;
use function strtolower;

/**
 * Template
 */
class Template
{
    public const CELLS_FOLDER = 'cells';

    public const ELEMENTS_FOLDER = 'elements';

    public const LAYOUTS_FOLDER = 'layouts';

    protected const FILE_EXTENSION = '.php';

    protected static array $paths = [];

    /**
     * Add a path for loading templates.
     *
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
     * Clear all paths.
     */
    public static function clear(): void
    {
        static::$paths = [];
    }

    /**
     * Get the paths.
     *
     * @return array The paths.
     */
    public static function getPaths(): array
    {
        return static::$paths;
    }

    /**
     * Find a file in paths.
     *
     * @param string $name The file name.
     * @param string $folder The file folder.
     * @return string|null The file path.
     */
    public static function locate(string $name, string $folder = ''): string|null
    {
        if (!str_ends_with($name, static::FILE_EXTENSION)) {
            $name .= static::FILE_EXTENSION;
        }

        foreach (static::$paths as $path) {
            $filePath = Path::join($path, $folder, $name);

            if (is_file($filePath)) {
                return $filePath;
            }
        }

        return null;
    }

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
     * Remove a path.
     *
     * @param string $path The path to remove.
     * @return bool TRUE if the path was removed, otherwise FALSE.
     */
    public static function removePath(string $path): bool
    {
        $path = Path::resolve($path);

        foreach (static::$paths as $i => $otherPath) {
            if ($otherPath !== $path) {
                continue;
            }

            array_splice(static::$paths, $i, 1);

            return true;
        }

        return false;
    }
}
