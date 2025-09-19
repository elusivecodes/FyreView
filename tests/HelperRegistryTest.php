<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Server\ServerRequest;
use Fyre\Utility\Traits\MacroTrait;
use Fyre\View\CellRegistry;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\Helper;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

use function class_uses;

final class HelperRegistryTest extends TestCase
{
    protected HelperRegistry $helperRegistry;

    protected View $view;

    public function testBuild(): void
    {
        $this->assertInstanceOf(
            Helper::class,
            $this->helperRegistry->build('Test', $this->view)
        );
    }

    public function testBuildInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->helperRegistry->build('Invalid', $this->view);
    }

    public function testFind(): void
    {
        $this->assertSame(
            'Tests\Mock\Helpers\TestHelper',
            $this->helperRegistry->find('Test')
        );
    }

    public function testFindInvalid(): void
    {
        $this->assertNull(
            $this->helperRegistry->find('Invalid')
        );
    }

    public function testGetNamespaces(): void
    {
        $this->assertSame(
            [
                'Tests\Mock\Helpers\\',
            ],
            $this->helperRegistry->getNamespaces()
        );
    }

    public function testHasNamespace(): void
    {
        $this->assertTrue(
            $this->helperRegistry->hasNamespace('\Tests\Mock\Helpers')
        );
    }

    public function testHasNamespaceInvalid(): void
    {
        $this->assertFalse(
            $this->helperRegistry->hasNamespace('\Tests\Mock\Invalid')
        );
    }

    public function testMacroable(): void
    {
        $this->assertContains(
            MacroTrait::class,
            class_uses(HelperRegistry::class)
        );
    }

    public function testNamespaceNoLeadingSlash(): void
    {
        $this->helperRegistry->clear();
        $this->helperRegistry->addNamespace('Tests\Mock\Helpers');

        $this->assertInstanceOf(
            Helper::class,
            $this->helperRegistry->build('Test', $this->view)
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        $this->helperRegistry->clear();
        $this->helperRegistry->addNamespace('\Tests\Mock\Helpers\\');

        $this->assertInstanceOf(
            Helper::class,
            $this->helperRegistry->build('Test', $this->view)
        );
    }

    public function testRemoveNamespace(): void
    {
        $this->assertSame(
            $this->helperRegistry,
            $this->helperRegistry->removeNamespace('\Tests\Mock\Helpers')
        );

        $this->assertFalse(
            $this->helperRegistry->hasNamespace('\Tests\Mock\Helpers')
        );
    }

    public function testRemoveNamespaceInvalid(): void
    {
        $this->assertSame(
            $this->helperRegistry,
            $this->helperRegistry->removeNamespace('\Tests\Mock\Invalid')
        );
    }

    protected function setUp(): void
    {
        $container = new Container();
        $container->singleton(Config::class);
        $container->singleton(TemplateLocator::class);
        $container->singleton(HelperRegistry::class);
        $container->singleton(CellRegistry::class);

        $this->helperRegistry = $container->use(HelperRegistry::class);
        $this->helperRegistry->addNamespace('\Tests\Mock\Helpers');

        $request = $container->build(ServerRequest::class);

        $this->view = $container->build(View::class, ['request' => $request]);
    }
}
