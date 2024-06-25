<?php
declare(strict_types=1);

namespace Fyre\View\Helpers;

use Fyre\Security\CspBuilder;
use Fyre\View\Helper;

use function hash;
use function random_bytes;

/**
 * CspHelper
 */
class CspHelper extends Helper
{
    /**
     * Generate a script nonce.
     *
     * @return string The script nonce.
     */
    public function scriptNonce(): string
    {
        return static::addNonce('script-src');
    }

    /**
     * Generate a style nonce.
     *
     * @return string The style nonce.
     */
    public function styleNonce(): string
    {
        return static::addNonce('style-src');
    }

    /**
     * Add a nonce for a directive.
     *
     * @param string $directive The directive.
     * @return string The nonce.
     */
    protected static function addNonce(string $directive): string
    {
        $nonce = static::generateNonce();
        $value = 'nonce-'.$nonce;

        $policies = CspBuilder::getPolicies();

        foreach ($policies as $key => $policy) {
            $policy = $policy->addDirective($directive, $value);

            CspBuilder::setPolicy($key, $policy);
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
