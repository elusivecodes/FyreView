<?php
declare(strict_types=1);

namespace Tests\Mock\Cells;

use Fyre\View\Cell;

class ExampleCell extends Cell
{

    public function test(int $value): void
    {
        $this->set('a', $value);
    }

}
