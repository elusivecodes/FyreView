<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Server\ServerRequest;
use Fyre\View\CellRegistry;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class CellTest extends TestCase
{
    protected View $view;

    public function testCamelCase(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Other',
            $this->view->cell('Example::otherTest')->render()
        );
    }

    public function testCell(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Value: 1',
            $this->view->render('test/cell')
        );
    }

    public function testCellInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->cell('Invalid')->render();
    }

    public function testCellInvalidMethod(): void
    {
        $this->expectException(ViewException::class);

        $this->view->cell('Test::invalid')->render();
    }

    public function testData(): void
    {
        $cell = $this->view->cell('Test');

        $this->assertSame(
            $cell,
            $cell->setData([
                'a' => 1,
            ])
        );

        $this->assertSame(
            [
                'a' => 1,
            ],
            $cell->getData()
        );
    }

    public function testGetView(): void
    {
        $this->assertInstanceOf(
            View::class,
            $this->view->cell('Test')->getView()
        );
    }

    public function testHelper(): void
    {
        $this->assertSame(
            'test',
            $this->view->cell('Test')->Test->test()
        );
    }

    public function testRender(): void
    {
        $this->assertSame(
            'Test',
            $this->view->cell('Test')->render()
        );
    }

    public function testRenderArguments(): void
    {
        $this->assertSame(
            'Value: 1',
            $this->view->cell('Example::test', ['value' => 1])->render()
        );
    }

    public function testSet(): void
    {
        $cell = $this->view->cell('Test');

        $this->assertSame(
            $cell,
            $cell->set('a', 1)
        );

        $this->assertSame(
            [
                'a' => 1,
            ],
            $cell->getData()
        );
    }

    protected function setUp(): void
    {
        $container = new Container();
        $container->singleton(Config::class);
        $container->singleton(TemplateLocator::class);
        $container->singleton(HelperRegistry::class);
        $container->singleton(CellRegistry::class);

        $container->use(CellRegistry::class)->addNamespace('\Tests\Mock\Cells');
        $container->use(HelperRegistry::class)->addNamespace('\Tests\Mock\Helpers');
        $container->use(TemplateLocator::class)->addPath('tests/Mock/templates');

        $request = $container->build(ServerRequest::class);

        $this->view = $container->build(View::class, ['request' => $request]);
    }
}