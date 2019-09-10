<?php

namespace App\Http\Controllers;

use Auth;

use App\SubCategory;
use App\Activity;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
	public function createAllSlugs () {
		$sub_categories = SubCategory::all();

		foreach ($sub_categories as $sub_category) {
			$slug = str_slug($sub_category->name);
			$sub_cat = SubCategory::where('slug', $slug)->first();
			$count = 0;

			while ($sub_cat && $sub_category->id != $sub_cat->id) {
				$count++;
				$slug = str_slug($sub_category->name . $count);
				$sub_cat = SubCategory::where('slug', $slug)->first();
			}

			$sub_category->slug = str_slug($slug);
			
			$sub_category->save();
		}

		return 'DONE!!!';
	}

    /*****************/
	/**** AJAX *******/
	/*****************/

	public function ajaxStore(Request $request)
	{
		$this->validate($request, [
			'name' => 'required'
		]);

		$sub_category = SubCategory::where(array('name' => $request->name))->first();

		if ($sub_category)
			return array('status' => 'ERROR', 'error' => '\'' . $request->name . '\' already exists.');

		$sub_category = new SubCategory;

		$sub_category->name = $request->name;
		$sub_category->icon = $request->icon;
		$sub_category->category_id = $request->category_id;
		$sub_category->save();

		$slug = str_slug($sub_category->name);
		$sub_cat = SubCategory::where('slug', $slug)->first();
		$count = 0;

		while ($sub_cat && $sub_category->id != $sub_cat->id) {
			$count++;
			$slug = str_slug($sub_category->name . $count);
			$sub_cat = SubCategory::where('slug', $slug)->first();
		}

		$sub_category->slug = str_slug($slug);
		
		$sub_category->save();

		return array('status' => 'OK', 'result' => $sub_category);
	}

	public function ajaxShow($id) {
		$user = Auth::user();

		$sub_category = SubCategory::find($id);

		if (!$sub_category)
			return array('status' => 'ERROR', 'error' => 'Sub-category not found.');

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

		return array('status' => 'OK', 'sub_category' => $sub_category);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required'
		]);

		$sub_category = SubCategory::find($id);

		if (!$sub_category)
			return array('status' => 'ERROR', 'error' => 'SubCategory not found.');

		$sub_category->name = $request->name;
		$sub_category->icon = $request->icon;
		$sub_category->category_id = $request->category_id;
		$sub_category->save();

		$slug = str_slug($sub_category->name);
		$sub_cat = SubCategory::where('slug', $slug)->first();
		$count = 0;

		while ($sub_cat && $sub_category->id != $sub_cat->id) {
			$count++;
			$slug = str_slug($sub_category->name . $count);
			$sub_cat = SubCategory::where('slug', $slug)->first();
		}

		$sub_category->slug = str_slug($slug);
		
		$sub_category->save();

		return array('status' => 'OK', 'result' => $sub_category);
	}

	public function ajaxDestroy($id)
	{
		$sub_category = SubCategory::find($id);

		if (!$sub_category)
			return array('status' => 'ERROR', 'error' => 'SubCategory not found.');

		$sub_category->delete();

		return array('status' => 'OK');
	}

	public function ajaxUploadLogo(Request $request, $id)
	{
		$sub_category = SubCategory::find($id);

		if (!$sub_category)
			return array('status' => 'ERROR', 'error' => 'Category not found.');

		if ($request->hasFile('image')) {
		    $ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'sub_categories' . $ds . $id . $ds . 'image';

			if ($request->file('image')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('image');
			    $filename = uniqid() . '.' . $file->guessClientExtension();

		    	if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
		    		$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

		    		$sub_category->icon = $pseudoFile;
		    		$sub_category->save();	    

					return array('status' => 'OK', 'result' => $sub_category);
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
