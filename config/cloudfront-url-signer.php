<?php

return [
    /*
     * The default expiration time of a URL in days.
     */
    'default_expiration_time_in_days' => 1,

    /*
     * The private key used to sign all URLs.
     */
    'private_key_path' => storage_path(env('AWS_CLOUDFRONT_PRIVATE_KEY_PATH', 'trusted-signer.pem')),

    /*
     * Identifies the CloudFront key pair associated
     * to the trusted signer which validates signed URLs.
     */
    'key_pair_id' => env('AWS_CLOUDFRONT_KEY_PAIR_ID', ''),

    /*
     * CloudFront API version, by default it uses the latest available.
     */
    'version' => env('AWS_CLOUDFRONT_API_VERSION', 'latest'),

];
