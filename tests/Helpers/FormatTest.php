<?php
declare(strict_types=1);

namespace Tests\Helpers;

use
    Fyre\Formatter\Formatter,
    Fyre\Server\ServerRequest,
    Fyre\Server\ClientResponse,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    Tests\Mock\TestController;

final class FormatTest extends TestCase
{

    protected View $view;

    public function testCurrency(): void
    {
        $this->assertSame(
            '$1,234.00',
            $this->view->Format->currency(1234)
        );
    }

    public function testNumber(): void
    {
        $this->assertSame(
            '1,234',
            $this->view->Format->number(1234)
        );
    }

    protected function setUp(): void
    {
        Formatter::setDefaultLocale('en-US');

        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new TestController($request, $response);

        $this->view = new View($controller);
    }

}
