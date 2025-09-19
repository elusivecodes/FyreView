<?php
declare(strict_types=1);

namespace Tests\Views;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Event\EventManager;
use Fyre\Server\ServerRequest;
use Fyre\Utility\Traits\MacroTrait;
use Fyre\View\CellRegistry;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

use function class_uses;

final class ViewTest extends TestCase
{
    use BlockTestTrait;
    use DataTestTrait;
    use ElementTestTrait;
    use LayoutTestTrait;
    use RenderTestTrait;

    protected TemplateLocator $templateLocator;

    protected View $view;

    public function testGetRequest(): void
    {
        $this->assertInstanceOf(
            ServerRequest::class,
            $this->view->getRequest()
        );
    }

    public function testMacroable(): void
    {
        $this->assertContains(
            MacroTrait::class,
            class_uses(View::class)
        );
    }

    public function testPathTrailingSlash(): void
    {
        $this->templateLocator->clear();
        $this->templateLocator->addPath('tests/Mock/templates/');

        $this->view->setLayout(null);

        $this->assertSame(
            'Test',
            $this->view->render('test/deep/test')
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

        $this->templateLocator = $container->use(TemplateLocator::class);
        $this->templateLocator->addPath('tests/Mock/templates');

        $request = $container->build(ServerRequest::class);

        $this->view = $container->build(View::class, ['request' => $request]);
    }
}
