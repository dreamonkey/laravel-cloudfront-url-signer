<?php

namespace Dreamonkey\CloudFrontUrlSigner;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CloudFrontUrlSignerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->setupConfig($this->app);
    }

    /**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function setupConfig(Application $app)
    {
        $source = realpath(__DIR__ . '/../config/cloudfront-url-signer.php');
        $this->publishes([$source => config_path('cloudfront-url-signer.php')]);
        $this->mergeConfigFrom($source, 'cloudfront-url-signer');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cloudfront-url-signer.php', 'cloudfront-url-signer');

        $config = config('cloudfront-url-signer');

        $this->app->singleton(UrlSigner::class, function () use ($config) {
            $cloudFrontParams = [
                'region' => $config['region'],
                'version' => $config['version']
            ];

            return new CloudFrontUrlSigner($cloudFrontParams, $config['key_pair_id'], $config['private_key']);
        });

        $this->app->alias(UrlSigner::class, 'cloudfront-url-signer');
    }
}
