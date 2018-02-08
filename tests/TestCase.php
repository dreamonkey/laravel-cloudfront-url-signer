<?php

namespace Dreamonkey\CloudFrontUrlSigner\Tests;

use Dreamonkey\CloudFrontUrlSigner\CloudFrontUrlSignerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CloudFrontUrlSignerServiceProvider::class,
        ];
    }
}
