<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Server\ServerRequest;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\Helper;
use Fyre\View\HelperRegistry;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class HelperRegistryTest extends TestCase
{
    protected View $view;

    public function testFind(): void
    {
        $this->assertSame(
            '\Tests\Mock\Helpers\TestHelper',
            HelperRegistry::find('Test')
        );
    }

    public function testFindInvalid(): void
    {
        $this->assertNull(
            HelperRegistry::find('Invalid')
        );
    }

    public function testGetNamespaces(): void
    {
        $this->assertSame(
            [
                '\Tests\Mock\Helpers\\',
            ],
            HelperRegistry::getNamespaces()
        );
    }

    public function testHasNamespace(): void
    {
        $this->assertTrue(
            HelperRegistry::hasNamespace('\Tests\Mock\Helpers')
        );
    }

    public function testHasNamespaceInvalid(): void
    {
        $this->assertFalse(
            HelperRegistry::hasNamespace('\Tests\Mock\Invalid')
        );
    }

    public function testLoad(): void
    {
        $this->assertInstanceOf(
            Helper::class,
            HelperRegistry::load('Test', $this->view)
        );
    }

    public function testLoadInvalid(): void
    {
        $this->expectException(ViewException::class);

        HelperRegistry::load('Invalid', $this->view);
    }

    public function testNamespaceNoLeadingSlash(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('Tests\Mock\Helpers');

        $this->assertInstanceOf(
            Helper::class,
            HelperRegistry::load('Test', $this->view)
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers\\');

        $this->assertInstanceOf(
            Helper::class,
            HelperRegistry::load('Test', $this->view)
        );
    }

    public function testRemoveNamespace(): void
    {
        $this->assertTrue(
            HelperRegistry::removeNamespace('\Tests\Mock\Helpers')
        );

        $this->assertFalse(
            HelperRegistry::hasNamespace('\Tests\Mock\Helpers')
        );
    }

    public function testRemoveNamespaceInvalid(): void
    {
        $this->assertFalse(
            HelperRegistry::removeNamespace('\Tests\Mock\Invalid')
        );
    }

    protected function setUp(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers');

        $request = new ServerRequest();

        $this->view = new View($request);
    }
}
