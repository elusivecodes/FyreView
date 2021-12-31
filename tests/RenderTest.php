<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\View\Exceptions\ViewException;

trait RenderTest
{

    public function testRenderData(): void
    {
        $this->view->setData([
            'a' => 1
        ]);

        $this->assertEquals(
            'Template: 1',
            $this->view->render('test/template')
        );
    }

    public function testRenderDeep(): void
    {
        $this->assertEquals(
            'Test',
            $this->view->render('test/deep/test')
        );
    }

    public function testRenderInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->render('invalid');
    }

}
