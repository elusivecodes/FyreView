<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait CloseTestTrait
{

    public function testClose(): void
    {
        $this->assertSame(
            '</form>',
            $this->view->Form->close()
        );
    }

}
