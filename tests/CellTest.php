<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Event\Event;
use Fyre\Event\EventManager;
use Fyre\Server\ServerRequest;
use Fyre\Utility\Path;
use Fyre\View\Cell;
use Fyre\View\CellRegistry;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;
use Tests\Mock\Cells\ExampleCell;

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

    public function testEventAfterAction(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('Cell.afterAction', function(Event $event, Cell $cell, string $action, array $args) use (&$ran): void {
            $ran = true;

            $this->assertInstanceOf(
                ExampleCell::class,
                $cell
            );

            $this->assertSame('test', $action);

            $this->assertSame([
                'value' => 1,
            ], $args);
        });

        $this->view->cell('Example::test', ['value' => 1])->render();

        $this->assertTrue($ran);
    }

    public function testEventAfterRender(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('Cell.afterRender', function(Event $event, string $filePath, string $content) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/cells/Example/test.php'),
                $filePath
            );

            $this->assertSame('Value: 1', $content);
        });

        $this->view->cell('Example::test', ['value' => 1])->render();

        $this->assertTrue($ran);
    }

    public function testEventBeforeAction(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('Cell.beforeAction', function(Event $event, Cell $cell, string $action, array $args) use (&$ran): void {
            $ran = true;

            $this->assertInstanceOf(
                ExampleCell::class,
                $cell
            );

            $this->assertSame('test', $action);

            $this->assertSame([
                'value' => 1,
            ], $args);
        });

        $this->view->cell('Example::test', ['value' => 1])->render();

        $this->assertTrue($ran);
    }

    public function testEventBeforeRender(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('Cell.beforeRender', function(Event $event, string $filePath) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/cells/Example/test.php'),
                $filePath
            );
        });

        $this->view->cell('Example::test', ['value' => 1])->render();

        $this->assertTrue($ran);
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
        $container->singleton(EventManager::class);

        $container->use(CellRegistry::class)->addNamespace('\Tests\Mock\Cells');
        $container->use(HelperRegistry::class)->addNamespace('\Tests\Mock\Helpers');
        $container->use(TemplateLocator::class)->addPath('tests/Mock/templates');

        $request = $container->build(ServerRequest::class);

        $this->view = $container->build(View::class, ['request' => $request]);
    }
}
