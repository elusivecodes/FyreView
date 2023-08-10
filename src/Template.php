<?php
declare(strict_types=1);

namespace Fyre\View;

use Fyre\Utility\Path;

use function array_splice;
use function in_array;
use function is_file;
use function str_ends_with;

/**
 * Template
 */
class Template
{

    public const ELEMENTS_FOLDER = 'elements';
    public const LAYOUTS_FOLDER = 'layouts';
    protected const FILE_EXTENSION = '.php';

    protected static array $paths = [];

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
     * Clear all paths.
     */
    public static function clear(): void
    {
        static::$paths = [];
    }

    /**
     * Get the paths.
     * @return array The paths.
     */
    public static function getPaths(): array
    {
        return static::$paths;
    }

    /**
     * Find a file in paths.
     * @param string $name The file name.
     * @param string $folder The file folder.
     * @return string|null The file path.
     */
    public static function locate(string $name, string $folder = ''): string|null
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
     * Remove a path.
     * @param string $path The path to remove.
     * @return bool TRUE if the path was removed, otherwise FALSE.
     */
    public static function removePath(string $path): bool
    {
        $path = Path::resolve($path);

        foreach (static::$paths AS $i => $otherPath) {
            if ($otherPath !== $path) {
                continue;
            }

            array_splice(static::$paths, $i, 1);

            return true;
        }

        return false;
    }

}
