<?php

namespace Dreamonkey\CloudFrontUrlSigner;

use Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidKeyPairId;
use Illuminate\Support\ServiceProvider;

class CloudFrontUrlSignerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/cloudfront-url-signer.php' => config_path('cloudfront-url-signer.php')], 'config');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cloudfront-url-signer.php', 'cloudfront-url-signer');

        $this->app->singleton(UrlSigner::class, function () {
            $config = config('cloudfront-url-signer');

            if ($config['key_pair_id'] === '') {
                throw new InvalidKeyPairId('Key pair id cannot be empty');
            }

            return new CloudFrontUrlSigner(new \Aws\CloudFront\UrlSigner($config['key_pair_id'], $config['private_key_path']));
        });

        $this->app->alias(UrlSigner::class, 'cloudfront-url-signer');
    }
}
