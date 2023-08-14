<?php
declare(strict_types=1);

namespace Tests;

use Fyre\View\Exceptions\ViewException;

trait BlockTestTrait
{

    public function testStart(): void
    {
        $this->assertSame(
            $this->view,
            $this->view->start('test')
        );

        echo 'A';

        $this->assertSame(
            $this->view,
            $this->view->end()
        );

        $this->view->start('test');
        echo 'B';
        $this->view->end();

        $this->assertSame(
            'B',
            $this->view->fetch('test')
        );
    }

    public function testAppend(): void
    {
        $this->view->start('test');
        echo 'A';
        $this->view->end();

        $this->assertSame(
            $this->view,
            $this->view->append('test')
        );

        echo 'B';
        $this->view->end();

        $this->assertSame(
            'AB',
            $this->view->fetch('test')
        );
    }

    public function testPrepend(): void
    {
        $this->view->start('test');
        echo 'A';
        $this->view->end();

        $this->assertSame(
            $this->view,
            $this->view->prepend('test')
        );

        echo 'B';
        $this->view->end();

        $this->assertSame(
            'BA',
            $this->view->fetch('test')
        );
    }

    public function testAssign(): void
    {
        $this->view->start('test');
        echo 'A';
        $this->view->end();

        $this->assertSame(
            $this->view,
            $this->view->assign('test', 'B')
        );

        $this->assertSame(
            'B',
            $this->view->fetch('test')
        );
    }

    public function testReset(): void
    {
        $this->view->start('test');
        echo 'A';
        $this->view->end();

        $this->assertSame(
            $this->view,
            $this->view->reset('test')
        );

        $this->assertSame(
            '',
            $this->view->fetch('test')
        );
    }

    public function testNestedBlocks(): void
    {
        $this->view->start('test');
        echo 'A';
        $this->view->start('other');
        echo 'B';
        $this->view->end();
        $this->view->end();

        $this->assertSame(
            'A',
            $this->view->fetch('test')
        );

        $this->assertSame(
            'B',
            $this->view->fetch('other')
        );
    }

    public function testUnopenedBlock(): void
    {
        $this->expectException(ViewException::class);

        $this->view->end();
    }

    public function testUnclosedBlock(): void
    {
        $this->expectException(ViewException::class);

        $this->view->setData([
            'a' => 1
        ]);

        $this->view->setLayout(null);
        $this->view->start('test');
        $this->view->render('test/template');
    }

}
