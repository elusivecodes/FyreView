<?php
declare(strict_types=1);

namespace Fyre\View;

/**
 * Helper
 */
abstract class Helper
{

    protected View $view;

    /**
     * New Helper constructor.
     * @param View $view The View.
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

}
