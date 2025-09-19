<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Http\Uri;
use Fyre\Router\Router;
use Fyre\Server\ServerRequest;
use Fyre\Utility\HtmlHelper;
use Fyre\Utility\Traits\MacroTrait;
use Fyre\View\Helper;
use Fyre\View\View;

/**
 * UrlHelper
 */
class UrlHelper extends Helper
{
    use MacroTrait;

    protected ServerRequest $request;

    /**
     * New Helper constructor.
     *
     * @param Router $router The Router.
     * @param View $view The View.
     * @param array $options The helper options.
     */
    public function __construct(
        protected Router $router,
        protected HtmlHelper $htmlHelper,
        View $view,
        array $options = []
    ) {
        parent::__construct($view, $options);

        $this->request = $this->view->getRequest();
    }

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
            $content = $this->htmlHelper->escape($content);
        }

        return '<a'.$this->htmlHelper->attributes($options).'>'.$content.'</a>';
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
            $baseUri = $this->router->getBaseUri();

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
        return $this->router->url($name, $arguments, $options);
    }
}
