<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Router\Router;
use Fyre\Utility\HtmlHelper;
use Fyre\View\Helper;

use function is_string;
use function preg_match;

/**
 * UrlHelper
 */
class UrlHelper extends Helper
{

    /**
     * Generate an anchor link for a destination.
     * @param string $content The link content.
     * @param string|array $destination The destination.
     * @param array $options The link options.
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
        if (is_string($destination) && preg_match('/^(?:(?:javascript|mailto|tel|sms):|#|\?|:?\/\/)/', $destination)) {
            return $destination;
        }

        return Router::build($destination, $options);
    }

}
