<?php

namespace Dreamonkey\CloudFrontUrlSigner;

interface UrlSigner
{
    /**
     * Get a secure URL to a controller action.
     *
     * @param string $url
     * @param mixed  $expiration
     *
     * @return string
     */
    public function sign(string $url, $expiration): string;
}
