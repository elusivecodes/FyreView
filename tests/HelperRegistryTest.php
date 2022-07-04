<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\View\Exceptions\ViewException,
    Fyre\View\Helper,
    Fyre\View\HelperRegistry,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class HelperRegistryTest extends TestCase
{

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

    public function testLoad(): void
    {
        $view = new View();

        $this->assertInstanceOf(
            Helper::class,
            HelperRegistry::load('Test', $view)
        );
    }

    public function testLoadInvalid(): void
    {
        $this->expectException(ViewException::class);

        $view =  new View();

        HelperRegistry::load('Invalid', $view);
    }

    public function testNamespaceNoLeadingSlash(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('Tests\Mock\Helpers');

        $view = new View();

        $this->assertInstanceOf(
            Helper::class,
            HelperRegistry::load('Test', $view)
        );
    }

    public function testNamespaceTrailingSlash(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers\\');

        $view = new View();

        $this->assertInstanceOf(
            Helper::class,
            HelperRegistry::load('Test', $view)
        );
    }

    protected function setUp(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers');
    }

}
