<?php
declare(strict_types=1);

namespace Tests\Views;

use Fyre\Event\Event;
use Fyre\Utility\Path;

trait LayoutTestTrait
{
    public function testDefaultLayout(): void
    {
        $this->assertSame(
            'default',
            $this->view->getLayout()
        );
    }

    public function testEventAfterLayout(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('View.afterLayout', function(Event $event, string $filePath, string $content) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/layouts/test.php'),
                $filePath
            );

            $this->assertSame(
                'Layout: 1'."\r\n".
                'Content: Template: 1',
                $content
            );
        });

        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout('test');

        $this->view->render('test/template');

        $this->assertTrue($ran);
    }

    public function testEventBeforeLayout(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('View.beforeLayout', function(Event $event, string $filePath) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/layouts/test.php'),
                $filePath
            );
        });

        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout('test');

        $this->view->render('test/template');

        $this->assertTrue($ran);
    }

    public function testRenderLayout(): void
    {
        $this->view->setData([
            'a' => 1,
        ]);

        $this->view->setLayout('test');

        $this->assertSame(
            'Layout: 1'."\r\n".
            'Content: Template: 1',
            $this->view->render('test/template')
        );
    }
}
