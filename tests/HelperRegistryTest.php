<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Server\ServerRequest,
    Fyre\Server\ClientResponse,
    Fyre\View\Exceptions\ViewException,
    Fyre\View\Helper,
    Fyre\View\HelperRegistry,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    Tests\Mock\TestController;

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

    protected function setUp(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers');

        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new TestController($request, $response);

        $this->view = new View($controller);
    }

}
