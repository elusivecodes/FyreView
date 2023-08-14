<?php
declare(strict_types=1);

namespace Tests;

use Fyre\View\Exceptions\ViewException;

trait ElementTestTrait
{

    public function testElementData(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Element: 2',
            $this->view->render('test/element')
        );
    }

    public function testElementDeep(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Test',
            $this->view->render('test/element_deep')
        );
    }

    public function testElementInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->render('test/element_invalid');
    }

}
