<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Utility\Formatter;
use Fyre\View\Helper;

use function
    call_user_func_array;

/**
 * FormatHelper
 */
class FormatHelper extends Helper
{

    /**
     * Call a FormBuilder method.
     * @param string $method The method.
     * @param array $arguments Arguments to pass to the method.
     */
    public function __call(string $method, array $arguments)
    {
        return call_user_func_array([Formatter::class, $method], $arguments);
    }

}
