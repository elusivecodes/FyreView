<?php
declare(strict_types=1);

namespace Tests\View;

use
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{

    protected View $view;

    use
        DataTest,
        ElementTest,
        RenderTest;

    public function testPathTrailingSlash(): void
    {
        View::clear();
        View::addPath('tests/templates/');

        $this->assertEquals(
            'Test',
            $this->view->render('test/deep/test')
        );
    }

    protected function setUp(): void
    {
        View::clear();
        View::addPath('tests/templates');

        $this->view = new View();
    }

}
