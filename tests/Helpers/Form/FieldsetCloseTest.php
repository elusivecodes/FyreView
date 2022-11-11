<?php
declare(strict_types=1);

namespace Tests\Helpers\Form;

trait FieldsetCloseTest
{

    public function testFieldsetClose(): void
    {
        $this->assertSame(
            '</fieldset>',
            $this->view->Form->fieldsetClose()
        );
    }

}
