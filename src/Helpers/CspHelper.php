<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Security\ContentSecurityPolicy;
use Fyre\Utility\Traits\MacroTrait;
use Fyre\View\Helper;
use Fyre\View\View;

use function hash;
use function random_bytes;

/**
 * CspHelper
 */
class CspHelper extends Helper
{
    use MacroTrait;

    /**
     * New CspHelper constructor.
     *
     * @param ContentSecurityPolicy $csp The ContentSecurityPolicy.
     * @param View $view The View.
     * @param array $options The helper options.
     */
    public function __construct(
        protected ContentSecurityPolicy $csp,
        View $view,
        array $options = []
    ) {
        parent::__construct($view, $options);
    }

    /**
     * Generate a script nonce.
     *
     * @return string The script nonce.
     */
    public function scriptNonce(): string
    {
        return $this->addNonce('script-src');
    }

    /**
     * Generate a style nonce.
     *
     * @return string The style nonce.
     */
    public function styleNonce(): string
    {
        return $this->addNonce('style-src');
    }

    /**
     * Add a nonce for a directive.
     *
     * @param string $directive The directive.
     * @return string The nonce.
     */
    protected function addNonce(string $directive): string
    {
        $nonce = static::generateNonce();
        $value = 'nonce-'.$nonce;

        $policies = $this->csp->getPolicies();

        foreach ($policies as $key => $policy) {
            $policy = $policy->addDirective($directive, $value);

            $this->csp->setPolicy($key, $policy);
        }

        return $nonce;
    }

    /**
     * Generate a nonce.
     *
     * @return string The nonce.
     */
    protected static function generateNonce(): string
    {
        return hash('sha1', random_bytes(12));
    }
}
