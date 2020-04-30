<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => cloverEnv('MAILGUN_DOMAIN'),
        'secret' => cloverEnv('MAILGUN_SECRET'),
        'endpoint' => cloverEnv('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => cloverEnv('SES_KEY'),
        'secret' => cloverEnv('SES_SECRET'),
        'region' => cloverEnv('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => cloverEnv('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => cloverEnv('STRIPE_KEY'),
        'secret' => cloverEnv('STRIPE_SECRET'),
        'webhook' => [
            'secret' => cloverEnv('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => cloverEnv('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

];
