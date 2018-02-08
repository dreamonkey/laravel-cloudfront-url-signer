<?php

namespace Dreamonkey\CloudFrontUrlSigner\Facades;

use Illuminate\Support\Facades\Facade;

class CloudFrontUrlSigner extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cloudfront-url-signer';
    }
}
