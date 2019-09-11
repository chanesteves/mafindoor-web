<?php

namespace App\Http\Controllers;

use Auth;
use Session;

use App\Annotation;
use App\Building;
use App\Category;
use App\SubCategory;
use App\User;
use App\Activity;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
	public function ajaxStore(Request $request) {
		$user = null;

    	if ($request->user_id)
    		$user = User::find($request->user_id)

		if (!$user)
			$user = Auth::user();

		$this->validate($request, [
			'request_type' => 'required'
		]);

		$activity = new Activity;

		if ($user)
			$activity->user_id = $user->id;
		$activity->object_id = $request->object_id;
		$activity->object_type = $request->object_type;
		$activity->request_path = \Request::getRequestUri();
		$activity->request_type = $request->request_type;

		if (strpos(\Request::getRequestUri(), 'api/') !== false)
			$activity->request_via = 'mobile';
		else
			$activity->request_via = 'web';

		$activity->save();		

		$log_building = null;
		$log_category = null;
		$log_sub_category = null;

		if ($request->object_type == 'App\\Annotation') {
			$annotation = Annotation::find($request->object_id);

			if ($annotation) {
				$log_building = $annotation->building;
				$log_sub_category = $annotation->sub_category;

				if ($log_sub_category)
					$log_category = $log_sub_category->category;
			}
		}

		if ($request->object_type == 'App\\SubCategory') {
			$sub_category = SubCategory::find($request->object_id);

			if ($sub_category)
				$log_category = $sub_category->category;
		}

		if ($log_building) {
			$sub_activity = new Activity;

			if ($user)
				$sub_activity->user_id = $user->id;
			$sub_activity->object_id = $log_building->id;
			$sub_activity->object_type = get_class($log_building);
			$sub_activity->request_path = \Request::getRequestUri();
			$sub_activity->request_type = $request->request_type;

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$sub_activity->request_via = 'mobile';
			else
				$sub_activity->request_via = 'web';

			$sub_activity->save();
		}

		if ($log_category) {
			$sub_activity = new Activity;

			if ($user)
				$sub_activity->user_id = $user->id;
			$sub_activity->object_id = $log_category->id;
			$sub_activity->object_type = get_class($log_category);
			$sub_activity->request_path = \Request::getRequestUri();
			$sub_activity->request_type = $request->request_type;

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$sub_activity->request_via = 'mobile';
			else
				$sub_activity->request_via = 'web';

			$sub_activity->save();
		}

		if ($log_sub_category) {
			$sub_activity = new Activity;

			if ($user)
				$sub_activity->user_id = $user->id;
			$sub_activity->object_id = $log_sub_category->id;
			$sub_activity->object_type = get_class($log_sub_category);
			$sub_activity->request_path = \Request::getRequestUri();
			$sub_activity->request_type = $request->request_type;

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$sub_activity->request_via = 'mobile';
			else
				$sub_activity->request_via = 'web';
			
			$sub_activity->save();
		}

		return array('status' => 'OK', 'result' => $activity, 'log_building' => $log_building, 'log_category' => $log_category, 'log_sub_category' => $log_sub_category);
	}
}
