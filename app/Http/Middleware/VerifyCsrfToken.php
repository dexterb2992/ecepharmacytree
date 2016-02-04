<?php

namespace ECEPharmacyTree\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    	'verifypayment/',
    	'verify_cash_payment/',
        'saveBranchPreference',
        'save_user_token',
        'upload_sc_id',
    ];
}
