<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Utility\Formatter;
use Fyre\View\Helper;
use Fyre\View\View;

/**
 * FormatHelper
 */
class FormatHelper extends Helper
{
    protected Formatter $formatter;

    /**
     * New FormatHelper constructor.
     *
     * @param Formatter $formatter The Formatter.
     * @param View $view The View.
     * @param array $options The helper options.
     */
    public function __construct(Formatter $formatter, View $view, array $options = [])
    {
        parent::__construct($view, $options);

        $this->formatter = $formatter;
    }

    /**
     * Call a Formatter method.
     *
     * @param string $method The method.
     * @param array $arguments Arguments to pass to the method.
     * @return mixed The formatted value.
     */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->formatter->$method(...$arguments);
    }
}
