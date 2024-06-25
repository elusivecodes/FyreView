<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Http\Uri;
use Fyre\Router\Router;
use Fyre\Utility\HtmlHelper;
use Fyre\View\Helper;

/**
 * UrlHelper
 */
class UrlHelper extends Helper
{
    /**
     * Generate an anchor link for a destination.
     *
     * @param string $content The link content.
     * @param array $options The link options.
     * @return string The anchor link.
     */
    public function link(string $content, array $options = []): string
    {
        $escape = $options['escape'] ?? true;

        unset($options['escape']);

        if ($escape) {
            $content = HtmlHelper::escape($content);
        }

        return '<a'.HtmlHelper::attributes($options).'>'.$content.'</a>';
    }

    /**
     * Generate a URL for a relative path.
     *
     * @param string $path The relative path.
     * @param array $options The path options.
     * @return string The URL.
     */
    public function path(string $path, array $options = []): string
    {
        $options['fullBase'] ??= false;

        if ($options['fullBase']) {
            $baseUri = Router::getBaseUri();

            return Uri::fromString($baseUri)
                ->resolveRelativeUri($path)
                ->getUri();
        }

        return Uri::fromString($path)
            ->getUri();
    }

    /**
     * Generate a URL for a named route.
     *
     * @param string $name The name.
     * @param array $arguments The route arguments
     * @param array $options The route options.
     * @return string The URL.
     */
    public function to(string $name, array $arguments = [], array $options = []): string
    {
        return Router::url($name, $arguments, $options);
    }
}
