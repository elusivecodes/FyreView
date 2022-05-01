<?php
declare(strict_types=1);

namespace Tests\Helpers;

use
    Fyre\CSRF\CsrfProtection,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

use function
    password_verify,
    preg_match;

final class FormTest extends TestCase
{

    protected View $view;

    public function testOpen(): void
    {
        $this->assertSame(
            '<form method="post" charset="UTF-8">',
            $this->view->Form->open()
        );
    }

    public function testInput(): void
    {
        $this->assertSame(
            '<input type="text" />',
            $this->view->Form->input()
        );
    }

    public function testCsrf(): void
    {
        $field = CsrfProtection::getField();

        $input = $this->view->Form->csrf();

        $this->assertMatchesRegularExpression(
            '/^<input name="'.$field.'" type="hidden" value="(.*)" \/>$/',
            $input
        );

        preg_match('/value="(.*)"/', $input, $match);

        $tokenHash = $match[1];
        $token = CsrfProtection::getToken();

        $this->assertTrue(
            password_verify($token, $tokenHash)
        );
    }

    protected function setUp(): void
    {
        $this->view = new View();
    }

}
