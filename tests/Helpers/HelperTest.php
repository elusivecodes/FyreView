<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Fyre\Server\ServerRequest;
use Fyre\View\Exceptions\ViewException;
use Fyre\View\HelperRegistry;
use Fyre\View\Template;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class HelperTest extends TestCase
{

    protected View $view;

    public function testHelper(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'test',
            $this->view->render('test/helper')
        );
    }

    public function testLoadHelperInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->loadHelper('Invalid');
    }

    public function testGetConfig(): void
    {
        $this->view->loadHelper('Test', [
            'value' => 1
        ]);

        $this->assertSame(
            [
                'value' => 1
            ],
            $this->view->Test->getConfig()
        );
    }

    public function testGetView(): void
    {
        $this->assertInstanceOf(
            View::class,
            $this->view->Test->getView()
        );
    }

    protected function setUp(): void
    {
        HelperRegistry::clear();
        HelperRegistry::addNamespace('\Tests\Mock\Helpers');

        Template::clear();
        Template::addPath('tests/Mock/templates');

        $request = new ServerRequest();

        $this->view = new View($request);
    }

}
