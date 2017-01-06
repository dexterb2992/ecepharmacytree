<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |282318
    */

    'mailgun' => [
        /**
        **Royette December 13, 2016
        **/
        //'domain' => 'sandbox1a698871994341728643aceacb1d340e.mailgun.org',
        //'domain' => 'sandbox8cb0fea402984f3ba7c9174b5cfd67b5.mailgun.org',
        'domain' => 'sandbox8fd6481f208d4e4789bdd722e93e2ce0.mailgun.org',
        //'secret' => 'key-bc8efec9d9e71e9c547cc2cd3c7ca6dd',
        //'secret' => 'key-fdc84fd7aefbdba498dface332812a29',
        'secret' => 'key-f65b75ad27d21f8c8307a2b83bbc63b0',
        // 'domain' => 'irishbusiness.ie',
        // 'secret' => 'key-4-n-fbbr6zlgdto6fdzzuqiqmjrko9x8',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => ECEPharmacyTree\User::class,
        'key'    => '',
        'secret' => '',
    ],

];
