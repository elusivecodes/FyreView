<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Server\ServerRequest;
use Fyre\View\Template;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{

    protected View $view;

    use BlockTestTrait;
    use DataTestTrait;
    use ElementTestTrait;
    use LayoutTestTrait;
    use RenderTestTrait;

    public function testGetRequest(): void
    {
        $this->assertInstanceOf(
            ServerRequest::class,
            $this->view->getRequest()
        );
    }

    public function testPathTrailingSlash(): void
    {
        Template::clear();
        Template::addPath('tests/Mock/templates/');

        $this->assertSame(
            'Test',
            $this->view->render('test/deep/test')
        );
    }

    protected function setUp(): void
    {
        Template::clear();
        Template::addPath('tests/Mock/templates');

        $request = new ServerRequest();

        $this->view = new View($request);
    }

}
