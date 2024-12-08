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

final class HelperTest extends TestCase
{
    protected View $view;

    public function testGetConfig(): void
    {
        $this->view->loadHelper('Test', [
            'value' => 1,
        ]);

        $this->assertSame(
            [
                'value' => 1,
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

    public function testHelper(): void
    {
        $this->view->setLayout(null);

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

    protected function setUp(): void
    {
        $container = new Container();
        $container->singleton(Config::class);
        $container->singleton(TemplateLocator::class);
        $container->singleton(HelperRegistry::class);
        $container->singleton(CellRegistry::class);

        $container->use(HelperRegistry::class)->addNamespace('\Tests\Mock\Helpers');
        $container->use(TemplateLocator::class)->addPath('tests/Mock/templates');

        $request = $container->build(ServerRequest::class);

        $this->view = $container->build(View::class, ['request' => $request]);
    }
}
