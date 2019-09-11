<?php

namespace App\Http\Controllers;

use Auth;

use App\Category;
use App\Activity;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /*****************/
	/**** AJAX *******/
	/*****************/

	public function ajaxStore(Request $request)
	{
		$this->validate($request, [
			'name' => 'required'
		]);

		$category = Category::where(array('name' => $request->name))->first();

		if ($category)
			return array('status' => 'ERROR', 'error' => '\'' . $request->name . '\' already exists.');

		$category = new Category;

		$category->name = $request->name;
		$category->icon = $request->icon;
		$category->save();

		return array('status' => 'OK', 'result' => $category);
	}

	public function ajaxShow(Request $request, $id) {
		$user = null;

    	if ($request->api_token && $request->api_token != '')
    		$user = User::where('api_token', $request->api_token)->first();

		if (!$user)
			$user = Auth::user();

		$category = Category::find($id);

		if (!$category)
			return array('status' => 'ERROR', 'error' => 'Category not found.');

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

		return array('status' => 'OK', 'category' => $category);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required'
		]);

		$category = Category::find($id);

		if (!$category)
			return array('status' => 'ERROR', 'error' => 'Category not found.');

		$category->name = $request->name;
		$category->icon = $request->icon;
		$category->save();

		return array('status' => 'OK', 'result' => $category);
	}

	public function ajaxDestroy($id)
	{
		$category = Category::find($id);

		if (!$category)
			return array('status' => 'ERROR', 'error' => 'Category not found.');

		$category->delete();

		return array('status' => 'OK');
	}

	public function ajaxUploadLogo(Request $request, $id)
	{
		$category = Category::find($id);

		if (!$category)
			return array('status' => 'ERROR', 'error' => 'Category not found.');

		if ($request->hasFile('image')) {
		    $ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'categories' . $ds . $id . $ds . 'image';

			if ($request->file('image')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('image');
			    $filename = uniqid() . '.' . $file->guessClientExtension();

		    	if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
		    		$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

		    		$category->icon = $pseudoFile;
		    		$category->save();	    

					return array('status' => 'OK', 'result' => $category);
			    }
			    else {
			    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading icon.');
		    	}
		    }
		    else {
		    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading icon.');
	    	}
		}
		else {
			return array('status' => 'ERROR', 'error' => 'Error encountered while uploading icon.');
		}
	}
}
