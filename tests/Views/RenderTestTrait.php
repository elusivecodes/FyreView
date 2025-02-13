<?php
declare(strict_types=1);

namespace Tests\Views;

use Fyre\Event\Event;
use Fyre\Utility\Path;
use Fyre\View\Exceptions\ViewException;

trait RenderTestTrait
{
    public function testEventAfterRender(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('View.afterRender', function(Event $event, string $filePath, string $content) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/test/template.php'),
                $filePath
            );

            $this->assertSame('Template: 1', $content);
        });

        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout(null);

        $this->view->render('test/template');

        $this->assertTrue($ran);
    }

    public function testEventBeforeRender(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('View.beforeRender', function(Event $event, string $filePath) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/test/template.php'),
                $filePath
            );
        });

        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout(null);

        $this->view->render('test/template');

        $this->assertTrue($ran);
    }

    public function testRenderData(): void
    {
        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout(null);

        $this->assertSame(
            'Template: 1',
            $this->view->render('test/template')
        );
    }

    public function testRenderDeep(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Test',
            $this->view->render('test/deep/test')
        );
    }

    public function testRenderInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->render('invalid');
    }
}
