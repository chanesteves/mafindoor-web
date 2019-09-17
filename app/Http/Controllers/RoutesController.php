<?php

namespace App\Http\Controllers;

use Auth;
use Session;

use App\User;
use App\Building;
use App\Floor;
use App\Activity;
use App\Route;
use App\Turn;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RoutesController extends Controller
{

    /*****************/
	/**** AJAX *******/
	/*****************/

	public function ajaxStore(Request $request)
	{
		$this->validate($request, [
			'turns' => 'required',
			'floor_id' => 'required'
		]);

		$first_turn = $request->turns[0];
		$last_turn = $request->turns[count($request->turns) - 1];

		$route = new Route;

		$route->origin_lat = $first_turn['latitude'];
		$route->origin_lng = $first_turn['longitude'];
		$route->destination_lat = $last_turn['latitude'];
		$route->destination_lng = $last_turn['longitude'];
		$route->floor_id = $request->floor_id;
		
		$route->save();

		foreach ($request->turns as $t) {
			$turn = new Turn;

			$turn->latitude = $t['latitude'];
			$turn->longitude = $t['longitude'];
			$turn->direction = $t['direction'];
			$turn->route_id = $route->id;
			$turn->step = $t['step'];
			$turn->save();
		}

		return array('status' => 'OK', 'result' => $route);
	}

	public function ajaxShow(Request $request, $id) {
		$route = Route::with('turns')->find($id);

		if (!$route)
			return array('status' => 'ERROR', 'error' => 'Route not found.');

		return array('status' => 'OK', 'route' => $route);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'turns' => 'required',
			'floor_id' => 'required'
		]);

		$route = Route::find($id);

		if (!$route)
			return array('status' => 'ERROR', 'error' => 'Route not found.');

		$turn_ids = [];

		foreach ($request->turns as $t) {
			$turn = null;

			if (array_key_exists('id', $t))
				$turn = Turn::find($t['id']);

			if (!$turn)
				$turn = new Turn;

			$turn->latitude = $t['latitude'];
			$turn->longitude = $t['longitude'];
			$turn->direction = $t['direction'];
			$turn->route_id = $route->id;
			$turn->step = $t['step'];
			$turn->save();

			$turn_ids[] = $turn->id;
		}

		Turn::where('route_id', $route->id)->whereNotIn('id', $turn_ids)->delete();

		return array('status' => 'OK', 'result' => $route);
	}

	public function ajaxDestroy($id)
	{
		$route = Route::find($id);

		if (!$route)
			return array('status' => 'ERROR', 'error' => 'Route not found.');

		$route->delete();

		return array('status' => 'OK');
	}
}
