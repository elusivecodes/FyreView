<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Utility\Path;
use Fyre\View\Template;
use PHPUnit\Framework\TestCase;

final class TemplateTest extends TestCase
{

    public function testAddPath(): void
    {
        Template::addPath('tests/Mock/templates1/');
        Template::addPath('tests/Mock/templates2/');

        $this->assertSame(
            [
                Path::resolve('tests/Mock/templates1/'),
                Path::resolve('tests/Mock/templates2/')
            ],
            Template::getPaths()
        );
    }

    public function testLocate(): void
    {
        Template::addPath('tests/Mock/templates/');

        $this->assertSame(
            Path::resolve('tests/Mock/templates/test/template.php'),
            Template::locate('template', 'test')
        );
    }

    public function testLocateDeep(): void
    {
        Template::addPath('tests/Mock/templates/');

        $this->assertSame(
            Path::resolve('tests/Mock/templates/test/deep/test.php'),
            Template::locate('deep/test', 'test')
        );
    }

    public function testRemovePath(): void
    {
        Template::addPath('tests/Mock/templates/');

        $this->assertTrue(
            Template::removePath('tests/Mock/templates/')
        );

        $this->assertEmpty(
            Template::getPaths()
        );
    }

    public function testRemovePathInvalid(): void
    {
        $this->assertFalse(
            Template::removePath('tests/Mock/invalid/')
        );
    }

    protected function setUp(): void
    {
        Template::clear();
    }

}
