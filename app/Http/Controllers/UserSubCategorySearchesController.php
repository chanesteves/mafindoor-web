<?php

namespace App\Http\Controllers;

use App\User;
use App\UserSubCategorySearch;

use Illuminate\Http\Request;

class UserSubCategorySearchesController extends Controller
{
    public function ajaxStore (Request $request) {
    	$user = null;

    	if ($request->api_key && $request->api_key != '')
    		$user = User::where('api_key', $request->api_key)->first();
    	
    	$user_sub_category_search = new UserSubCategorySearch;

    	if ($user)
    		$user_sub_category_search->user_id = $user->id;
    	else
    		$user_sub_category_search->user_id = 0;

    	$user_sub_category_search->sub_category_id = $request->sub_category_id;
    	$user_sub_category_search->save();

    	return array('status' => 'OK');
    }
}
