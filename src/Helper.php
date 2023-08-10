<?php
declare(strict_types=1);

namespace Fyre\View;

use function array_replace;

/**
 * Helper
 */
abstract class Helper
{

    protected static array $defaults = [];

    protected View $view;

    protected array $config;

    /**
     * New Helper constructor.
     * @param View $view The View.
     * @param array $options The helper options.
     */
    public function __construct(View $view, array $options = [])
    {
        $this->view = $view;

        $this->config = array_replace(static::$defaults, $options);
    }

    /**
     * Get the helper config.
     * @return array The helper config.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get the View.
     * @return View The View.
     */
    public function getView(): View
    {
        return $this->view;
    }

}
