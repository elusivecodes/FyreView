<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use
    Fyre\HtmlHelper\HtmlHelper,
    Fyre\Router\Router,
    Fyre\View\Helper;

/**
 * Url
 */
class Url extends Helper
{

    /**
     * Generate an anchor link for a destination.
     * @param string $content The link content.
     * @param string|array $destination The destination.
     * @param array $options THe link options.
     * @return string The anchor link.
     */
    public function link(string $content, string|array $destination, array $options = []): string
    {
        $escape = $options['escape'] ?? true;
        $fullBase = $options['fullBase'] ?? false;

        unset($options['escape']);
        unset($options['fullBase']);

        $options['href'] = static::to($destination, ['fullBase' => $fullBase]);

        if ($escape) {
            $content = HtmlHelper::escape($content);
        }

        return '<a'.HtmlHelper::attributes($options).'>'.$content.'</a>';
    }

    /**
     * Generate a URL for a destination.
     * @param string|array $destination The destination.
     * @param array $options The route options.
     * @return string The URL.
     */
    public function to(string|array $destination, array $options = []): string
    {
        return Router::build($destination, $options);
    }

}
