<?php

namespace Dreamonkey\CloudFrontUrlSigner\Tests;

use DateTime;
use DateTimeZone;
use League\Uri\Components\Query;
use League\Uri\Http;

class SignatureGenerationTest extends TestCase
{
    private $dummyPrivateKeyPath = 'tests/dummy-key.pem';
    private $dummyKeyPairId = 'dummyKeyPairId';
    private $dummyUrl = 'http://myapp.com';

    protected function setUp()
    {
        parent::setUp();

        config(['cloudfront-url-signer.key_pair_id' => $this->dummyKeyPairId]);
        config(['cloudfront-url-signer.private_key_path' => $this->dummyPrivateKeyPath]);
    }

    /** @test */
    public function it_registered_cloudfront_url_signer_in_the_container()
    {
        $instance = $this->app['cloudfront-url-signer'];

        $this->assertInstanceOf(\Dreamonkey\CloudFrontUrlSigner\CloudFrontUrlSigner::class, $instance);
    }

    /**
     * @test
     *
     * @expectedException \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidKeyPairId
     */
    public function it_will_throw_an_exception_for_an_empty_key_pair_id()
    {
        config(['cloudfront-url-signer.key_pair_id' => '']);

        /** @noinspection PhpUnhandledExceptionInspection */
        sign($this->dummyUrl);
    }

    /** @test */
    public function it_can_sign_an_url_that_expires_at_a_certain_time()
    {
        $expiration = DateTime::createFromFormat('d/m/Y H:i:s', '10/08/2025 18:15:44',
            new DateTimeZone('Europe/Brussels'));

        /** @noinspection PhpUnhandledExceptionInspection */
        $signedUrl = sign($this->dummyUrl, $expiration);

        $this->assertEquals($expiration->getTimestamp(), $this->getSignedUrlExpirationTimestamp($signedUrl));
    }

    /** @test */
    public function it_can_sign_an_url_that_expires_after_a_relative_amount_of_days()
    {
        $expiration = 30;

        /** @noinspection PhpUnhandledExceptionInspection */
        $signedUrl = sign($this->dummyUrl, $expiration);

        $this->assertLessThanOrEqual(60, (new DateTime())->modify($expiration . ' days')->getTimestamp() - $this->getSignedUrlExpirationTimestamp($signedUrl));
    }

    /**
     * @test
     *
     * @expectedException \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration
     */
    public function it_does_not_allow_expiration_in_the_past_when_integer_is_given()
    {
        $expiration = -5;

        sign($this->dummyUrl, $expiration);
    }

    /**
     * @test
     *
     * @expectedException \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration
     */
    public function it_does_not_allow_expiration_in_the_past_when_datetime_is_given()
    {
        $expiration = DateTime::createFromFormat('d/m/Y H:i:s', '10/08/2005 18:15:44');

        sign($this->dummyUrl, $expiration);
    }

    /**
     * @param string $signedUrl
     * @return int
     */
    private function getSignedUrlExpirationTimestamp(string $signedUrl): int
    {
        return (int)(new Query(Http::createFromString($signedUrl)->getQuery()))->getParam('Expires');
    }
}
