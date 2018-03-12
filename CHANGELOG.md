# Changelog

All notable changes to `laravel-cloudfront-url-signer` will be documented in this file.

## Unreleased
- Updated CHANGELOG to cover undocumented releases

## 1.0.0 - 2018-02-19
- Added Laravel 5.6 compliance

## 0.1.5 - 2018-02-18
- getExpirationTimestamp now returns int value instead of string to overcome an AWS SDK bug

## 0.1.4 - 2018-02-13
- Refactored tests
- Switched to UrlSigner class instead of CloudFrontClient

## 0.1.3 - 2018-02-09
- Fixed README typo
- $expiration default behaviour moved to internal `sign()` method instead of helper

## 0.1.2 - 2018-02-09

- Fixed config publishing
- $expiration default behaviour moved to internal `sign()` method instead of helper

## 0.1.1 - 2018-02-09

- $expiration parameter of `sign()` helper now defaults to the value defined in the configuration file when not defined

## 0.1.1 - 2018-02-09

- Added `sign()` helper

## 0.1.0 - 2018-02-09

- Initial release
