<?php
declare(strict_types=1);

namespace Tests\Helpers;

use
    Fyre\CSP\CspBuilder,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class CSPTest extends TestCase
{

    protected View $view;

    public function testScriptNonce(): void
    {
        CspBuilder::create('policy', []);

        $nonce = $this->view->CSP->scriptNonce();

        $policy = CspBuilder::get('policy');

        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{40}$/',
            $nonce
        );

        $this->assertSame(
            'script-src \'nonce-'.$nonce.'\';',
            $policy->getHeader()
        );
    }

    public function testStyleNonce(): void
    {
        CspBuilder::create('policy', []);

        $nonce = $this->view->CSP->styleNonce();

        $policy = CspBuilder::get('policy');

        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{40}$/',
            $nonce
        );

        $this->assertSame(
            'style-src \'nonce-'.$nonce.'\';',
            $policy->getHeader()
        );
    }

    protected function setUp(): void
    {
        $this->view = new View();
    }

}
