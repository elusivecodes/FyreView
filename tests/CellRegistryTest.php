<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Server\ServerRequest;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\Cell;
use Fyre\View\CellRegistry;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class CellRegistryTest extends TestCase
{

    protected View $view;

    public function testFind(): void
    {
        $this->assertSame(
            '\Tests\Mock\Cells\TestCell',
            CellRegistry::find('Test')
        );
    }

    public function testFindInvalid(): void
    {
        $this->assertNull(
            CellRegistry::find('Invalid')
        );
    }

    public function testGetNamespaces(): void
    {
        $this->assertSame(
            [
                '\Tests\Mock\Cells\\'
            ],
            CellRegistry::getNamespaces()
        );
    }

    public function testHasNamespace(): void
    {
        $this->assertTrue(
            CellRegistry::hasNamespace('\Tests\Mock\Cells')
        );
    }

    public function testHasNamespaceInvalid(): void
    {
        $this->assertFalse(
            CellRegistry::hasNamespace('\Tests\Mock\Invalid')
        );
    }

    public function testRemoveNamespace(): void
    {
        $this->assertTrue(
            CellRegistry::removeNamespace('\Tests\Mock\Cells')
        );

        $this->assertFalse(
            CellRegistry::hasNamespace('\Tests\Mock\Cells')
        );
    }

    public function testRemoveNamespaceInvalid(): void
    {
        $this->assertFalse(
            CellRegistry::removeNamespace('\Tests\Mock\Invalid')
        );
    }

    public function testLoad(): void
    {
        $this->assertInstanceOf(
            Cell::class,
            CellRegistry::load('Test', $this->view)
        );
    }

    public function testLoadInvalid(): void
    {
        $this->expectException(ViewException::class);

        CellRegistry::load('Invalid', $this->view);
    }

    public function testNamespaceNoLeadingSlash(): void
    {
        CellRegistry::clear();
        CellRegistry::addNamespace('Tests\Mock\Cells');

        $this->assertInstanceOf(
            Cell::class,
            CellRegistry::load('Test', $this->view)
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        CellRegistry::clear();
        CellRegistry::addNamespace('\Tests\Mock\Cells\\');

        $this->assertInstanceOf(
            Cell::class,
            CellRegistry::load('Test', $this->view)
        );
    }

    protected function setUp(): void
    {
        CellRegistry::clear();
        CellRegistry::addNamespace('\Tests\Mock\Cells');

        $request = new ServerRequest();

        $this->view = new View($request);
    }

}
