<?php

namespace Dreamonkey\CloudFrontUrlSigner;

use Aws\CloudFront\CloudFrontClient;
use Illuminate\Contracts\Foundation\Application;
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

            $cloudFrontClient = new CloudFrontClient([
                // CloudFront is global, us-east-1 region must be used
                // See https://docs.aws.amazon.com/general/latest/gr/rande.html?shortFooter=true#cf_region
                'region' => 'us-east-1',
                'version' => $config['version']
            ]);

            return new CloudFrontUrlSigner($cloudFrontClient, $config['private_key_path'], $config['key_pair_id']);
        });

        $this->app->alias(UrlSigner::class, 'cloudfront-url-signer');
    }
}
