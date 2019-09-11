<?php

namespace App\Http\Controllers;

use Auth;

use App\User;
use App\UserSubCategorySearch;
use App\SubCategory;
use App\Activity;


use Illuminate\Http\Request;

class UserSubCategorySearchesController extends Controller
{
    public function ajaxStore (Request $request) {
    	$user = null;

    	if ($request->user_id)
    		$user = User::find($request->user_id)
    	
    	$user_sub_category_search = new UserSubCategorySearch;

    	if ($user)
    		$user_sub_category_search->user_id = $user->id;
    	else
    		$user_sub_category_search->user_id = 0;

    	$user_sub_category_search->sub_category_id = $request->sub_category_id;
    	$user_sub_category_search->save();

        $sub_category = SubCategory::find($request->sub_category_id);

        if ($sub_category) {
            $activity = new Activity;

            if ($user)
                $activity->user_id = $user->id;
            $activity->object_id = $sub_category->id;
            $activity->object_type = get_class($sub_category);
            $activity->request_path = \Request::getRequestUri();
            $activity->request_type = 'search';

            if (strpos(\Request::getRequestUri(), 'api/') !== false)
                $activity->request_via = 'mobile';
            else
                $activity->request_via = 'web';

            $activity->save();

            $category = $sub_category->category;

            if ($category) {
                $activity = new Activity;

                if ($user)
                    $activity->user_id = $user->id;
                $activity->object_id = $category->id;
                $activity->object_type = get_class($category);
                $activity->request_path = \Request::getRequestUri();
                $activity->request_type = 'search';

                if (strpos(\Request::getRequestUri(), 'api/') !== false)
                    $activity->request_via = 'mobile';
                else
                    $activity->request_via = 'web';
            
                $activity->save();
            }
        }

    	return array('status' => 'OK');
    }
}
