<?php
declare(strict_types=1);

namespace Tests\Mock\Helpers;

use Fyre\View\Helper;

class TestHelper extends Helper
{

    public function test(): string
    {
        return 'test';
    }

}
