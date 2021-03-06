<?php
declare(strict_types=1);

namespace Tests\Helpers;

use
    Fyre\View\Exceptions\ViewException,
    Fyre\View\Helper,
    Fyre\View\HelperRegistry,
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

    public function testLoadHelperInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->loadHelper('Invalid');
    }

    public function testGetConfig(): void
    {
        $this->view->loadHelper('Test', [
            'value' => 1
        ]);

        $this->assertSame(
            [
                'value' => 1
            ],
            $this->view->Test->getConfig()
        );
    }

    public function testGetView(): void
    {
        $this->assertInstanceOf(
            View::class,
            $this->view->Test->getView()
        );
    }

    protected function setUp(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers');

        View::clear();
        View::addPath('tests/Mock/templates');

        $this->view = new View();
    }

}
