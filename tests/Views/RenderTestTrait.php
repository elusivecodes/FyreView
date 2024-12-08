<?php
declare(strict_types=1);

namespace Tests\Views;

use Fyre\View\Exceptions\ViewException;

trait RenderTestTrait
{
    public function testRenderData(): void
    {
        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout(null);

        $this->assertSame(
            'Template: 1',
            $this->view->render('test/template')
        );
    }

    public function testRenderDeep(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
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
