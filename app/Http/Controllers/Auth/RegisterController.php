<?php

namespace App\Http\Controllers\Auth;

use Input;
use Hash;
use Config;
use Session;
use Auth;
use Notification;

use App\User;
use App\Person;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Request as _Request;

use App\Notifications\UserCreated;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:persons',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getRegister()
    {
       return view('auth.register')->with(array());
    }

    public function ajaxRegister(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',            
            'username' => 'required',            
            'password' => 'required'
        ]);

        $user = User::where(array('username' => $request->username))->first();

        $person = null;

        if($user)
            return array('status' => 'ERROR', 'error' => 'Username '. $request->username . ' already exists.');

        $person = Person::where('email', $request->email)->first();

        if ($person && $person->user)
            return array('status' => 'ERROR', 'error' => 'Email '. $request->email . ' already exists.');

        $person = new Person;

        $person->first_name = $request->first_name;
        $person->last_name = $request->last_name;
        
        if ($request->gender)
            $person->gender = $request->gender;
        
        $person->email = $request->email;
        $person->save();

        $user = new User;

        $user->id = $person->id;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->signup_via = 'email';
        $user->email_verification_code = uniqid();
        $user->api_token = uniqid();

        $user->save();

        try {
            $person->user->notify(new UserCreated($person->user));
        }
        catch (\Exception $e) {
            // insert code here
        }

        $user = User::with('person')->find($person->id);

        return array('status' => 'OK', 'person' => $person, 'user' => $user);
    }
}
