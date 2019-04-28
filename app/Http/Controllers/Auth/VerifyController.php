<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use Config;

use App\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Request as _Request;

class VerifyController extends Controller
{
    public function __construct()
    {
        // insert code here
    }

    public function getVerifyEmail(Request $request)
    {
        $now = date('Y-m-d H:i:s');

        $code = trim($request->confirm);

        $user = User::where('email_verification_code', $code)->first();

        if (!$code || $code = '' || !$user)
            return view('auth.verify-email')->with(array('status' => 'ERROR', 'error' => 'Invalid verification code'));

        $user->email_verified_at = $now;
        $user->save();

        return view('auth.verify-email')->with(array('status' => 'OK', 'message' => 'Your email address ' . ($user->person && $user->person->email != '' ? $user->person->email . ' ' : '') . 'has been successfully verified.'));
    }
}
