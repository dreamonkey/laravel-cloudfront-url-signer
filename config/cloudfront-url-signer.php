<?php

return [
    /*
     * The default expiration time of a URL in days.
     */
    'default_expiration_time_in_days' => 1,

    /*
     * The private key used to sign all URLs.
     */
    'private_key' => env('CLOUDFRONT_PRIVATE_KEY', ''),

    /*
     * Identifies the CloudFront key pair associated
     * to the trusted signer which validates signed URLs.
     */
    'key_pair_id' => env('CLOUDFRONT_KEY_PAIR_ID', ''),

    /*
     * AWS region to connect to.
     */
    'region' => env('AWS_DEFAULT_REGION', 'us-west-2'),

    /*
     * CloudFront API version, by default it uses the latest available.
     */
    'version' => env('CLOUDFRONT_API_VERSION', 'latest'),

];