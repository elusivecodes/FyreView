<?php
declare(strict_types=1);

namespace Tests\Views;

use Fyre\Event\Event;
use Fyre\Utility\Path;
use Fyre\View\Exceptions\ViewException;

trait ElementTestTrait
{
    public function testElementData(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Element: 2',
            $this->view->render('test/element')
        );
    }

    public function testElementDeep(): void
    {
        $this->view->setLayout(null);

        $this->assertSame(
            'Test',
            $this->view->render('test/element_deep')
        );
    }

    public function testElementInvalid(): void
    {
        $this->expectException(ViewException::class);

        $this->view->render('test/element_invalid');
    }

    public function testEventAfterElement(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('View.afterElement', function(Event $event, string $filePath, string $content) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/elements/element.php'),
                $filePath
            );

            $this->assertSame('Element: 2', $content);
        });

        $this->view->setLayout(null);

        $this->view->render('test/element');

        $this->assertTrue($ran);
    }

    public function testEventBeforeElement(): void
    {
        $ran = false;
        $this->view->getEventManager()->on('View.beforeElement', function(Event $event, string $filePath) use (&$ran): void {
            $ran = true;

            $this->assertSame(
                Path::normalize('./tests/Mock/templates/elements/element.php'),
                $filePath
            );
        });

        $this->view->setLayout(null);

        $this->view->render('test/element');

        $this->assertTrue($ran);
    }
}
