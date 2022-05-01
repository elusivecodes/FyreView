<?php
declare(strict_types=1);

namespace Tests\Helpers;

use
    Fyre\Router\Router,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{

    protected View $view;

    public function testLink(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="/home">Title</a>',
            $this->view->Url->link('Title', [
                'controller' => 'Home'
            ])
        );
    }

    public function testLinkAttributes(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a class="test" href="/home">Title</a>',
            $this->view->Url->link('Title', [
                'controller' => 'Home'
            ], [
                'class' => 'test'
            ])
        );
    }

    public function testLinkEscape(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="/home">&lt;i&gt;Title&lt;/i&gt;</a>',
            $this->view->Url->link('<i>Title</i>', [
                'controller' => 'Home'
            ])
        );
    }

    public function testLinkNoEscape(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="/home"><i>Title</i></a>',
            $this->view->Url->link('<i>Title</i>', [
                'controller' => 'Home'
            ], [
                'escape' => false
            ])
        );
    }

    public function testLinkFullBase(): void
    {
        Router::setBaseUri('https://test.com/');
        Router::get('home', 'Home');

        $this->assertSame(
            '<a href="https://test.com/home">Test</a>',
            $this->view->Url->link('Test', [
                'controller' => 'Home'
            ], [
                'fullBase' => true
            ])
        );
    }

    public function testTo(): void
    {
        Router::get('home', 'Home');

        $this->assertSame(
            '/home',
            $this->view->Url->to([
                'controller' => 'Home'
            ])
        );
    }

    public function testToFullbase(): void
    {
        Router::setBaseUri('https://test.com/');
        Router::get('home', 'Home');

        $this->assertSame(
            'https://test.com/home',
            $this->view->Url->to([
                'controller' => 'Home'
            ], [
                'fullBase' => true
            ])
        );
    }

    protected function setUp(): void
    {
        Router::clear();
        Router::setAutoRoute(false);
        Router::setDefaultNamespace('Tests\Controller');

        $this->view = new View();
    }

}
