<?php

namespace Dreamonkey\CloudFrontUrlSigner;

use DateTime;
use Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration;
use League\Uri\Http;

class CloudFrontUrlSigner implements UrlSigner
{
    /**
     * CloudFront client object.
     *
     * @var \Aws\CloudFront\UrlSigner
     */
    private $urlSigner;

    /**
     * @param \Aws\CloudFront\UrlSigner $urlSigner
     */
    public function __construct(\Aws\CloudFront\UrlSigner $urlSigner)
    {
        $this->urlSigner = $urlSigner;
    }

    /**
     * Get a secure URL to a controller action.
     *
     * @param string $url
     * @param \DateTime|int|null $expiration
     *
     * @return string
     * @throws \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration
     */
    public function sign(string $url, $expiration = null): string
    {
        $resourceKey = Http::createFromString($url);

        $expiration = $this->getExpirationTimestamp($expiration ??
            config('cloudfront-url-signer.default_expiration_time_in_days'));

        return $this->urlSigner->getSignedUrl($resourceKey, $expiration);
    }

    /**
     * Check if a timestamp is in the future.
     *
     * @param int $timestamp
     *
     * @return bool
     */
    protected function isFuture(int $timestamp): bool
    {
        return ((int)$timestamp) >= (new DateTime())->getTimestamp();
    }

    /**
     * Retrieve the expiration timestamp for a link based on an absolute DateTime or a relative number of days.
     *
     * @param \DateTime|int $expiration The expiration date of this link.
     *                                  - DateTime: The value will be used as expiration date
     *                                  - int: The expiration time will be set to X days from now
     *
     * @return string
     * @throws \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration
     */
    protected function getExpirationTimestamp($expiration): string
    {
        if (is_int($expiration)) {
            $expiration = (new DateTime())->modify((int)$expiration . ' days');
        }

        if (!$expiration instanceof DateTime) {
            throw new InvalidExpiration('Expiration date must be an instance of DateTime or an integer');
        }

        if (!$this->isFuture($expiration->getTimestamp())) {
            throw new InvalidExpiration('Expiration date must be in the future');
        }

        return (string)$expiration->getTimestamp();
    }
}
