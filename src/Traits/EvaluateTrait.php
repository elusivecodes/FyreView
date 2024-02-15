<?php
declare(strict_types=1);

namespace Fyre\View\Traits;

use function extract;
use function func_get_arg;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;

/**
 * EvaluateTrait
 */
trait EvaluateTrait
{

    /**
     * Render and inject data into a file.
     * @param string $filePath The file path.
     * @param array $data The data to inject.
     * @return string The rendered file.
     */
    protected function evaluate(string $filePath, array $data): string
    {
        extract($data);

        try {
            ob_start();

            include func_get_arg(0);

            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }

}
