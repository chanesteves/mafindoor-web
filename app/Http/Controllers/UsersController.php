<?php

namespace App\Http\Controllers;

use Hash;

use App\User;
use App\UserBuilding;

use Illuminate\Http\Request;

class UsersController extends Controller
{
	public function ajaxUpdate(Request $request, $id){
        $this->validate($request, [
            'username' => 'required',            
            'password' => 'required'
        ]);

        $user = User::find($id);

        if (!$user)
            return array('status' => 'ERROR', 'error' => 'User does not exist.');

        $user->username = $request->username;
        $user->password = Hash::make($request->password);

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
