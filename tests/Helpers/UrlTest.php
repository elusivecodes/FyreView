<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Fyre\Router\Router;
use Fyre\Server\ServerRequest;
use Fyre\View\View;
use HomeController;
use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    protected View $view;

    public function testLink(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="/home">Title</a>',
            $this->view->Url->link('Title', [
                'href' => '/home',
            ])
        );
    }

    public function testLinkAttributes(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a class="test" href="/home">Title</a>',
            $this->view->Url->link('Title', [
                'class' => 'test',
                'href' => '/home',
            ])
        );
    }

    public function testLinkEscape(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="/home">&lt;i&gt;Title&lt;/i&gt;</a>',
            $this->view->Url->link('<i>Title</i>', [
                'href' => '/home',
            ])
        );
    }

    public function testLinkNoEscape(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="/home"><i>Title</i></a>',
            $this->view->Url->link('<i>Title</i>', [
                'href' => '/home',
                'escape' => false,
            ])
        );
    }

    public function testPath(): void
    {
        $this->assertSame(
            '/assets/test.txt',
            $this->view->Url->path('assets/test.txt')
        );
    }

    public function testPathFullbase(): void
    {
        $this->assertSame(
            'https://test.com/assets/test.txt',
            $this->view->Url->path('assets/test.txt', ['fullBase' => true])
        );
    }

    public function testTo(): void
    {
        Router::get('home', HomeController::class, ['as' => 'home']);

        $this->assertSame(
            '/home',
            $this->view->Url->to('home')
        );
    }

    public function testToArguments(): void
    {
        Router::get('home/(:segment)', HomeController::class, ['as' => 'home']);

        $this->assertSame(
            '/home/1',
            $this->view->Url->to('home', [1])
        );
    }

    public function testToFullbase(): void
    {
        Router::get('home', HomeController::class, ['as' => 'home']);

        $this->assertSame(
            'https://test.com/home',
            $this->view->Url->to('home', options: [
                'fullBase' => true,
            ])
        );
    }

    protected function setUp(): void
    {
        Router::clear();
        Router::setBaseUri('https://test.com/');

        $request = new ServerRequest();

        $this->view = new View($request);
    }
}
