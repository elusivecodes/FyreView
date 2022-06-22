<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\View\Exceptions\ViewException;

trait LayoutTest
{

    public function testRenderLayout(): void
    {
        $this->view->setData([
            'a' => 1
        ]);

        $this->view->setLayout('test');

        $this->assertSame(
            'Layout: 1'."\r\n".
            'Content: Template: 1',
            $this->view->render('test/template')
        );
    }

    public function testDefaultLayout(): void
    {
        $this->assertSame(
            'default',
            $this->view->getLayout()
        );
    }

}
