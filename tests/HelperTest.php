<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\View\Exceptions\ViewException,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class HelperTest extends TestCase
{

    public function testHelper(): void
    {
        $this->assertEquals(
            'test',
            $this->view->render('test/helper')
        );
    }

    public function testHelperInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->render('test/helper_invalid');
    }

    public function testGetView(): void
    {
        $this->assertInstanceOf(
            View::class,
            $this->view->Test->getView()
        );
    }

    public function testNamespaceLeadingSlash(): void
    {
        View::clear();
        View::addNamespace('\Tests\Helpers');
        View::addPath('tests/templates');

        $this->assertEquals(
            'test',
            $this->view->render('test/helper')
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        View::clear();
        View::addNamespace('Tests\Helpers\\');
        View::addPath('tests/templates');

        $this->assertEquals(
            'test',
            $this->view->render('test/helper')
        );
    }

    protected function setUp(): void
    {
        View::clear();
        View::addNamespace('Tests\Helpers');
        View::addPath('tests/templates');

        $this->view = new View();
    }

}
