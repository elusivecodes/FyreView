<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Server\ServerRequest;
use Fyre\Utility\Traits\MacroTrait;
use Fyre\View\Cell;
use Fyre\View\CellRegistry;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

use function class_uses;

final class CellRegistryTest extends TestCase
{
    protected CellRegistry $cellRegistry;

    protected View $view;

    public function testBuild(): void
    {
        $this->assertInstanceOf(
            Cell::class,
            $this->cellRegistry->build('Test', $this->view)
        );
    }

    public function testBuildInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->cellRegistry->build('Invalid', $this->view);
    }

    public function testFind(): void
    {
        $this->assertSame(
            'Tests\Mock\Cells\TestCell',
            $this->cellRegistry->find('Test')
        );
    }

    public function testFindInvalid(): void
    {
        $this->assertNull(
            $this->cellRegistry->find('Invalid')
        );
    }

    public function testGetNamespaces(): void
    {
        $this->assertSame(
            [
                'Tests\Mock\Cells\\',
            ],
            $this->cellRegistry->getNamespaces()
        );
    }

    public function testHasNamespace(): void
    {
        $this->assertTrue(
            $this->cellRegistry->hasNamespace('\Tests\Mock\Cells')
        );
    }

    public function testHasNamespaceInvalid(): void
    {
        $this->assertFalse(
            $this->cellRegistry->hasNamespace('\Tests\Mock\Invalid')
        );
    }

    public function testMacroable(): void
    {
        $this->assertContains(
            MacroTrait::class,
            class_uses(CellRegistry::class)
        );
    }

    public function testNamespaceNoLeadingSlash(): void
    {
        $this->cellRegistry->clear();
        $this->cellRegistry->addNamespace('Tests\Mock\Cells');

        $this->assertInstanceOf(
            Cell::class,
            $this->cellRegistry->build('Test', $this->view)
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        $this->cellRegistry->clear();
        $this->cellRegistry->addNamespace('\Tests\Mock\Cells\\');

        $this->assertInstanceOf(
            Cell::class,
            $this->cellRegistry->build('Test', $this->view)
        );
    }

    public function testRemoveNamespace(): void
    {
        $this->assertSame(
            $this->cellRegistry,
            $this->cellRegistry->removeNamespace('\Tests\Mock\Cells')
        );

        $this->assertFalse(
            $this->cellRegistry->hasNamespace('\Tests\Mock\Cells')
        );
    }

    public function testRemoveNamespaceInvalid(): void
    {
        $this->assertSame(
            $this->cellRegistry,
            $this->cellRegistry->removeNamespace('\Tests\Mock\Invalid')
        );
    }

    protected function setUp(): void
    {
        $container = new Container();
        $container->singleton(Config::class);
        $container->singleton(TemplateLocator::class);
        $container->singleton(HelperRegistry::class);
        $container->singleton(CellRegistry::class);

        $this->cellRegistry = $container->use(CellRegistry::class);
        $this->cellRegistry->addNamespace('\Tests\Mock\Cells');

        $request = $container->build(ServerRequest::class);

        $this->view = $container->build(View::class, ['request' => $request]);
    }
}
