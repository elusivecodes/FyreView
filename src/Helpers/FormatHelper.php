<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Utility\Formatter;
use Fyre\View\Helper;

/**
 * FormatHelper
 */
class FormatHelper extends Helper
{
    /**
     * Call a Formatter method.
     *
     * @param string $method The method.
     * @param array $arguments Arguments to pass to the method.
     * @return mixed The formatted value.
     */
    public function __call(string $method, array $arguments): mixed
    {
        return Formatter::$method(...$arguments);
    }
}
