<?php

namespace App\Http\Controllers;

use Hash;

use App\User;
use App\Person;
use App\UserBuilding;
use App\UserRole;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function ajaxStore (Request $request) {
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

        $roles = array_diff($request->roles, [0]);

        $roles = array_unique($roles);

        foreach ($roles as $role) {
            $user_role = UserRole::where(array('user_id' => $person->id, 'role_id' => $role))->first();

            if (!$user_role)
                $user_role = new UserRole;

            $user_role->user_id = $person->id;
            $user_role->role_id = $role;
            $user_role->save();
        }

        $user_roles = UserRole::where('user_id', $person->id)->get();

        foreach ($user_roles as $user_role) {
            if (!in_array($user_role->role_id, $roles))
                $user_role->forceDelete();
        }


        $user->roles()->sync($roles);

        $user->save();

        $user = User::with('person')->find($person->id);

        return array('status' => 'OK', 'person' => $person, 'user' => $user);
    }

	public function ajaxUpdate(Request $request, $id){
        $user = User::find($id);

        if (!$user)
            return array('status' => 'ERROR', 'error' => 'User does not exist.');

        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $user->save();

        $person = $user->person;

        if (!$person && $person->user)
            $person = new Person;

        $person->first_name = $request->first_name;
        $person->last_name = $request->last_name;
        
        if ($request->gender)
            $person->gender = $request->gender;
        
        $person->email = $request->email;
        $person->save();

        $roles = array_diff($request->roles, [0]);

        $roles = array_unique($roles);

        $user->roles()->sync($roles);

        $user->save();

        $user = User::with('person')->find($user->id);

        return array('status' => 'OK', 'person' => $user->person, 'user' => $user);
    }

    public function ajaxShowUser (Request $request) {
    	$user = null;

    	if ($request->api_token && $request->api_token != '')
    		$user = User::with('person')->where('api_token', $request->api_token)->first();

    	return array('status' => 'OK', 'user' => $user);
    }

    public function ajaxShow ($id) {
        $user = User::with('person', 'roles')->find($id);

        return array('status' => 'OK', 'user' => $user);
    }

    public function ajaxDestroy($id){
        $user = User::find($id);

        if (!$user)
            return array('status' => 'ERROR', 'error' => 'User does not exist.');

        $user->delete();

        return array('status' => 'OK');
    }

    public function ajaxUnlockBuilding (Request $request, $id) {
        $now = date('Y-m-d H:i:s');

        $user_building = UserBuilding::where(array('user_id' => $id, 'building_id' => $request->building_id))->first();

        if (!$user_building) {
            $user_building = new UserBuilding;

            $user_building->user_id = $id;
            $user_building->building_id = $request->building_id;
        }

        $user_building->unlocked_at = $now;
        $user_building->save();

        return array('status' => 'OK');
    }
}
