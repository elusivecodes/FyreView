<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{

    protected View $view;

    use
        DataTest,
        ElementTest,
        LayoutTest,
        RenderTest;

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

        $this->view = new View();
    }

}
