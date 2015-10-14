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
    |
    */

    'mailgun' => [
        'domain' => 'sandbox8cb0fea402984f3ba7c9174b5cfd67b5.mailgun.org',
        'secret' => 'key-fdc84fd7aefbdba498dface332812a29',
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
