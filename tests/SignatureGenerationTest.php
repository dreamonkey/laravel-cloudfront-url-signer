<?php

namespace Dreamonkey\CloudFrontUrlSigner\Tests;

use Aws\CloudFront\CloudFrontClient;
use DateTime;
use DateTimeZone;
use Dreamonkey\CloudFrontUrlSigner\CloudFrontUrlSigner;

class SignatureGenerationTest extends TestCase
{
    private $dummyUrl = 'http://myapp.com';
    private $dummyPrivateKeyPath = 'dummy/path/key.pem';
    private $dummyKeyPairId = 'dummyKeyPairId';

    private $mockCloudFrontClient;

    protected function setUp()
    {
        parent::setUp();
        $this->mockCloudFrontClient = $this->createMock(CloudFrontClient::class);
        $this->mockCloudFrontClient->method('getSignedUrl')->willReturn('dummysignedurl');
    }

    /** @test */
    public function it_registered_cloudfront_url_signer_in_the_container()
    {
        config(['cloudfront-url-signer.key_pair_id' => $this->dummyKeyPairId]);
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
        /** @noinspection PhpUnhandledExceptionInspection */
        new CloudFrontUrlSigner($this->mockCloudFrontClient, $this->dummyPrivateKeyPath, '');
    }

    /**
     * @test
     *
     * @expectedException \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidPrivateKeyPath
     */
    public function it_will_throw_an_exception_for_an_empty_private_key_path()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        new CloudFrontUrlSigner($this->mockCloudFrontClient, '', $this->dummyKeyPairId);
    }

    /** @test */
    public function it_can_sign_a_signed_url_that_expires_at_a_certain_time()
    {
        $expiration = DateTime::createFromFormat('d/m/Y H:i:s', '10/08/2115 18:15:44',
            new DateTimeZone('Europe/Brussels'));

        /** @noinspection PhpUnhandledExceptionInspection */
        $urlSigner = (new CloudFrontUrlSigner($this->mockCloudFrontClient,
            $this->dummyPrivateKeyPath, $this->dummyKeyPairId));

        /** @noinspection PhpUnhandledExceptionInspection */
        $signedUrl = $urlSigner->sign($this->dummyUrl, $expiration);

        $this->assertTrue(is_string($signedUrl));
    }

    /** @test */
    public function it_can_sign_a_signed_url_that_expires_after_a_relative_amount_of_days()
    {
        $expiration = 30;

        /** @noinspection PhpUnhandledExceptionInspection */
        $urlSigner = (new CloudFrontUrlSigner($this->mockCloudFrontClient,
            $this->dummyPrivateKeyPath, $this->dummyKeyPairId));

        /** @noinspection PhpUnhandledExceptionInspection */
        $signedUrl = $urlSigner->sign($this->dummyUrl, $expiration);

        $this->assertTrue(is_string($signedUrl));
    }

    /**
     * @test
     *
     * @expectedException \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration
     */
    public function it_does_not_allow_expiration_in_the_past_when_integer_is_given()
    {
        $expiration = -5;

        /** @noinspection PhpUnhandledExceptionInspection */
        $urlSigner = (new CloudFrontUrlSigner($this->mockCloudFrontClient,
            $this->dummyPrivateKeyPath, $this->dummyKeyPairId));

        $urlSigner->sign($this->dummyUrl, $expiration);
    }

    /**
     * @test
     *
     * @expectedException \Dreamonkey\CloudFrontUrlSigner\Exceptions\InvalidExpiration
     */
    public function it_does_not_allow_expiration_in_the_past_when_datetime_is_given()
    {
        $expiration = DateTime::createFromFormat('d/m/Y H:i:s', '10/08/2005 18:15:44');

        /** @noinspection PhpUnhandledExceptionInspection */
        $urlSigner = (new CloudFrontUrlSigner($this->mockCloudFrontClient,
            $this->dummyPrivateKeyPath, $this->dummyKeyPairId));

        $urlSigner->sign($this->dummyUrl, $expiration);
    }
}
