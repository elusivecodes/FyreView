<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Utility\Path;
use Fyre\Utility\Traits\MacroTrait;
use Fyre\View\TemplateLocator;
use PHPUnit\Framework\TestCase;

use function class_uses;

final class TemplateLocatorTest extends TestCase
{
    protected TemplateLocator $templateLocator;

    public function testAddPath(): void
    {
        $this->templateLocator->addPath('tests/Mock/templates1');
        $this->templateLocator->addPath('tests/Mock/templates2');

        $this->assertSame(
            [
                Path::resolve('tests/Mock/templates1'),
                Path::resolve('tests/Mock/templates2'),
            ],
            $this->templateLocator->getPaths()
        );
    }

    public function testLocate(): void
    {
        $this->templateLocator->addPath('tests/Mock/templates');

        $this->assertSame(
            Path::resolve('tests/Mock/templates/test/template.php'),
            $this->templateLocator->locate('template', 'test')
        );
    }

    public function testLocateDeep(): void
    {
        $this->templateLocator->addPath('tests/Mock/templates');

        $this->assertSame(
            Path::resolve('tests/Mock/templates/test/deep/test.php'),
            $this->templateLocator->locate('deep/test', 'test')
        );
    }

    public function testMacroable(): void
    {
        $this->assertContains(
            MacroTrait::class,
            class_uses(TemplateLocator::class)
        );
    }

    public function testRemovePath(): void
    {
        $this->templateLocator->addPath('tests/Mock/templates');

        $this->assertSame(
            $this->templateLocator,
            $this->templateLocator->removePath('tests/Mock/templates')
        );

        $this->assertEmpty(
            $this->templateLocator->getPaths()
        );
    }

    public function testRemovePathInvalid(): void
    {
        $this->assertSame(
            $this->templateLocator,
            $this->templateLocator->removePath('tests/Mock/invalid')
        );
    }

    protected function setUp(): void
    {
        $this->templateLocator = new TemplateLocator();
    }
}
