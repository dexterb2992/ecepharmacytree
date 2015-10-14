<?php

namespace ECEPharmacyTree\Http\Controllers\Auth;

use ECEPharmacyTree\User;
use Validator;
use ECEPharmacyTree\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = "auth/login";

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $messages = [
            'fname.required' => 'What is your First name?',
            'lname.required' => 'We need to know your Last name.',
            'email.required' => 'We need your valid email address.',
        ];

        $rules = [
            'fname' => 'required|max:255|min:3',
            'lname' => 'required|max:255|min:3',
            'email' => 'required|email|max:255|unique:users',
            'password'         => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'branch_id' => 'required',
            'access_level' => 'required'
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'fname' => $data['fname'],
            'mname' => $data['mname'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'access_level' => $data['access_level'],
            'branch_id' => $data['branch_id'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
