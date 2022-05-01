<?php
declare(strict_types=1);

namespace Tests\Helpers;

use
    Fyre\View\Exceptions\ViewException,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class HelperTest extends TestCase
{

    protected View $view;

    public function testHelper(): void
    {
        $this->assertSame(
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
        View::addNamespace('\Tests\Mock\Helpers');
        View::addPath('tests/Mock/templates');

        $this->assertSame(
            'test',
            $this->view->render('test/helper')
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        View::clear();
        View::addNamespace('Tests\Mock\Helpers\\');
        View::addPath('tests/Mock/templates');

        $this->assertSame(
            'test',
            $this->view->render('test/helper')
        );
    }

    protected function setUp(): void
    {
        View::clear();
        View::addNamespace('Tests\Mock\Helpers');
        View::addPath('tests/Mock/templates');

        $this->view = new View();
    }

}
