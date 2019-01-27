<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth, View, Session, Request;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view)
        {
            if (Auth::user()) {
                $user = Auth::user();

                if (!$user->email_verified_at && $user->person && $user->person->email != '' && $user->signup_via == 'email') {
                    if (trim($user->email_verification_code) == '') {
                        $user->email_verification_code = uniqid();
                        $user->save();
                    }

                    Session::put('warning', 'Please verify your email address ' . $user->person->email . ' by clicking this <a href="/auth/verifyEmail?confirm=' . $user->email_verification_code . '" target="_blank">link</a>.');
                }
                else {
                    Session::put('warning', null);
                }

                $user->last_logged_at = date('Y-m-d H:i:s');
                $user->save();
            }

            $view->with(array());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}