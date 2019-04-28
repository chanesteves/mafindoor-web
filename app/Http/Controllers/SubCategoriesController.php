<?php

namespace App\Http\Controllers;

use Auth;

use App\SubCategory;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
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

		return array('status' => 'OK', 'result' => $sub_category);
	}

	public function ajaxShow($id) {
		$sub_category = SubCategory::find($id);

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
