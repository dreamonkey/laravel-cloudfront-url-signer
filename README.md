# Create CloudFront signed URLs in Laravel 5.6+

Easy to use Laravel 6+ wrapper around the official AWS PHP SDK which allows to sign URLs to access Private Content through CloudFront CDN

Inspired by [laravel-url-signer](https://github.com/spatie/laravel-url-signer)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dreamonkey/laravel-cloudfront-url-signer.svg?style=flat-square)](https://packagist.org/packages/dreamonkey/laravel-cloudfront-url-signer)
[![Total Downloads](https://img.shields.io/packagist/dt/dreamonkey/laravel-cloudfront-url-signer.svg?style=flat-square)](https://packagist.org/packages/dreamonkey/laravel-cloudfront-url-signer)

This package can create canned policies signed URLs for CloudFront which expires after a given time. This is done by wrapping the AWS SDK method adding a Laravel-style configuration and accessibility.

This is how you can create signed URL that's valid for 30 days:

```php
// With Facade
CloudFrontUrlSigner::sign('https://myapp.com/resource', 30);

// With helper
sign('https://myapp.com/resource', 30);
```

The output is compliant with [CloudFront specifications](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/private-content-creating-signed-url-canned-policy.html)

## Installation

The package can be installed via Composer:

```
composer require dreamonkey/laravel-cloudfront-url-signer
```

## Configuration

The configuration file can optionally be published via:

```
php artisan vendor:publish --provider="Dreamonkey\CloudFrontUrlSigner\CloudFrontUrlSignerServiceProvider"
```

This is the content of the file:

```php
return [
    /*
     * The default expiration time of a URL in seconds.
     */
    'default_expiration_time_in_seconds' => 1,

    /*
     * The private key used to sign all URLs.
     */
    'private_key_path' => storage_path(env('CLOUDFRONT_PRIVATE_KEY_PATH', 'trusted-signer.pem')),

    /*
     * Identifies the CloudFront key pair associated
     * to the trusted signer which validates signed URLs.
     */
    'key_pair_id' => env('CLOUDFRONT_KEY_PAIR_ID', ''),

    /*
     * CloudFront API version, by default it uses the latest available.
     */
    'version' => env('CLOUDFRONT_API_VERSION', 'latest'),

];
```

## Usage

### Signing URLs

URL's can be signed with the `sign` method:

```php
CloudFrontUrlSigner::sign('https://myapp.com/resource');
```

By default the lifetime of an URL is one day. This value can be change in the config-file.
If you want a custom life time, you can specify the number of days the URL should be valid:

```php
// The generated URL will be valid for 5 days.
CloudFrontUrlSigner::sign('https://myapp.com/resource', 5);
```

For fine grained control, you may also pass a `DateTime` instance as the second parameter. The url
will be valid up to that moment. This example uses Carbon for convenience:

```php
// This URL will be valid up until 2 hours from the moment it was generated.
CloudFrontUrlSigner::sign('https://myapp.com/resource', Carbon\Carbon::now()->addHours(2) );
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email p.caleffi@dreamonkey.com instead of using the issue tracker.

## Credits

- [Paolo Caleffi](https://github.com/IlCallo)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
