<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use
    Fyre\CSRF\CsrfProtection,
    Fyre\FormBuilder\FormBuilder,
    Fyre\View\Helper;

use function
    call_user_func_array;

/**
 * FormHelper
 */
class FormHelper extends Helper
{

    /**
     * Call a FormBuilder method.
     * @param string $method The method.
     * @param array $arguments Arguments to pass to the method.
     */
    public function __call(string $method, array $arguments)
    {
        return call_user_func_array([FormBuilder::class, $method], $arguments);
    }

    /**
     * Render a CSRF token input element.
     * @return string The input HTML.
     */
    public function csrf()
    {
        return FormBuilder::hidden(CsrfProtection::getField(), [
            'value' => CsrfProtection::getTokenHash()
        ]);
    }

}
