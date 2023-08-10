<?php
declare(strict_types=1);

namespace Tests\Helpers;

use Fyre\Security\CspBuilder;
use Fyre\Server\ServerRequest;
use Fyre\View\View;
use PHPUnit\Framework\TestCase;

final class CSPTest extends TestCase
{

    protected View $view;

    public function testScriptNonce(): void
    {
        CspBuilder::createPolicy(CspBuilder::DEFAULT, []);

        $nonce = $this->view->CSP->scriptNonce();

        $policy = CspBuilder::getPolicy(CspBuilder::DEFAULT);

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
        CspBuilder::createPolicy(CspBuilder::DEFAULT, []);

        $nonce = $this->view->CSP->styleNonce();

        $policy = CspBuilder::getPolicy(CspBuilder::DEFAULT);

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
        $request = new ServerRequest();

        $this->view = new View($request);
    }

}
