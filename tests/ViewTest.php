<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Controller\Controller,
    Fyre\Server\ServerRequest,
    Fyre\Server\ClientResponse,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    Tests\Mock\TestController;

final class ViewTest extends TestCase
{

    protected View $view;

    use
        BlockTest,
        DataTest,
        ElementTest,
        LayoutTest,
        RenderTest;

    public function testGetController(): void
    {
        $this->assertInstanceOf(
            Controller::class,
            $this->view->getController()
        );
    }

    public function testPathTrailingSlash(): void
    {
        View::clear();
        View::addPath('tests/Mock/templates/');

        $this->assertSame(
            'Test',
            $this->view->render('test/deep/test')
        );
    }

    protected function setUp(): void
    {
        View::clear();
        View::addPath('tests/Mock/templates');

        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new TestController($request, $response);

        $this->view = new View($controller);
    }

}
