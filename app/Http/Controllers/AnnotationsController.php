<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Session;

use App\User;
use App\Building;
use App\Floor;
use App\Annotation;
use App\SubCategory;
use App\Activity;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AnnotationsController extends Controller
{
	public function createAllSlugs () {
		$annotations = Annotation::all();

		foreach ($annotations as $annotation) {
			$slug = str_slug($annotation->name);
			$anno = Annotation::where('slug', $slug)->first();
			$count = 0;

			while ($anno && $annotation->id != $anno->id) {
				$count++;
				$slug = str_slug($annotation->name . $count);
				$anno = Annotation::where('slug', $slug)->first();
			}

			$annotation->slug = str_slug($slug);
			
			$annotation->save();
		}

		return 'DONE!!!';
	}

	public function exportAnnotations (Request $request) {
		$floor = Floor::find($request->floor_id);

		$annotations = Annotation::where('floor_id', $floor->id)->get();

		$cells = array();

		$cells[] = array('column' => 'A', 'row' => 1, 'value' => 'Name');
		$cells[] = array('column' => 'B', 'row' => 1, 'value' => 'Logo');
		$cells[] = array('column' => 'C', 'row' => 1, 'value' => 'Longitude');
		$cells[] = array('column' => 'D', 'row' => 1, 'value' => 'Latitude');
		$cells[] = array('column' => 'E', 'row' => 1, 'value' => 'Sub Category');

		$row = 2;

		foreach ($annotations as $annotation) {
			$cells[] = array('column' => 'A', 'row' => $row, 'value' => $annotation->name);
			$cells[] = array('column' => 'B', 'row' => $row, 'value' => $annotation->logo);
			$cells[] = array('column' => 'C', 'row' => $row, 'value' => $annotation->longitude);
			$cells[] = array('column' => 'D', 'row' => $row, 'value' => $annotation->latitude);
			$cells[] = array('column' => 'E', 'row' => $row, 'value' => $annotation->sub_category ? $annotation->sub_category->name : '');

			$row++;
		}

    	return Excel::create('annotations', function($excel) use ($cells, $floor) {
		    $excel->sheet(($floor->building ? $floor->building->name . ' ' : '') . $floor->name, function($sheet) use ($cells) {		    	
				if ($cells) {
					foreach ($cells as $cell) {
						$sheet->setCellValueExplicit($cell['column'] . ($cell['row']), $cell['value'], \PHPExcel_Cell_DataType::TYPE_STRING);
					}
				}
		    });
		})->download('xlsx');
	}

    /*****************/
	/**** AJAX *******/
	/*****************/

	public function ajaxStore(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'map_name' => 'required',
			'longitude' => 'required',
			'latitude' => 'required',
			'min_zoom' => 'required',
			'max_zoom' => 'required',
			'sub_category_id' => 'required'
		]);

		$annotation = new Annotation;

		$annotation->name = $request->name;
		$annotation->map_name = $request->map_name;
		$annotation->longitude = $request->longitude;
		$annotation->latitude = $request->latitude;
		$annotation->min_zoom = $request->min_zoom;
		$annotation->max_zoom = $request->max_zoom;
		$annotation->sub_category_id = $request->sub_category_id;
		$annotation->floor_id = $request->floor_id;
		$annotation->save();

		$slug = str_slug($annotation->name);
		$anno = Annotation::where('slug', $slug)->first();
		$count = 0;

		while ($anno && $annotation->id != $anno->id) {
			$count++;
			$slug = str_slug($annotation->name . $count);
			$anno = Annotation::where('slug', $slug)->first();
		}

		$annotation->slug = str_slug($slug);
		
		$annotation->save();

		return array('status' => 'OK', 'result' => $annotation);
	}

	public function ajaxShow(Request $request, $id) {
		$user = null;

    	if ($request->api_key && $request->api_key != '')
    		$user = User::where('api_key', $request->api_key)->first();

		if (!$user)
			$user = Auth::user();

		$annotation = Annotation::find($id);

		if (!$annotation)
			return array('status' => 'ERROR', 'error' => 'Annotation not found.');

		$activity = new Activity;

		if ($user)
			$activity->user_id = $user->id;
		$activity->object_id = $annotation->id;
		$activity->object_type = get_class($annotation);
		$activity->request_path = \Request::getRequestUri();
		$activity->request_type = 'search';

		if (strpos(\Request::getRequestUri(), 'api/') !== false)
			$activity->request_via = 'mobile';
		else
			$activity->request_via = 'web';

		$activity->save();

		$sub_category = $annotation->sub_category;

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
		}

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

		$building = $annotation->floor ? $annotation->floor->building;

		if ($building) {
			$activity = new Activity;

			if ($user)
				$activity->user_id = $user->id;
			$activity->object_id = $building->id;
			$activity->object_type = get_class($building);
			$activity->request_path = \Request::getRequestUri();
			$activity->request_type = 'search';

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$activity->request_via = 'mobile';
			else
				$activity->request_via = 'web';
			
			$activity->save();
		}

		return array('status' => 'OK', 'annotation' => $annotation);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required',
			'map_name' => 'required',
			'longitude' => 'required',
			'latitude' => 'required',
			'min_zoom' => 'required',
			'max_zoom' => 'required',
			'sub_category_id' => 'required',
			'floor_id' => 'required'
		]);

		$annotation = Annotation::find($id);

		if (!$annotation)
			return array('status' => 'ERROR', 'error' => 'Annotation not found.');

		$annotation->name = $request->name;
		$annotation->map_name = $request->map_name;
		$annotation->longitude = $request->longitude;
		$annotation->latitude = $request->latitude;
		$annotation->min_zoom = $request->min_zoom;
		$annotation->max_zoom = $request->max_zoom;
		$annotation->floor_id = $request->floor_id;
		$annotation->sub_category_id = $request->sub_category_id;
		$annotation->save();

		$slug = str_slug($annotation->name);
		$anno = Annotation::where('slug', $slug)->first();
		$count = 0;

		while ($anno && $annotation->id != $anno->id) {
			$count++;
			$slug = str_slug($annotation->name . $count);
			$anno = Annotation::where('slug', $slug)->first();
		}

		$annotation->slug = str_slug($slug);
		
		$annotation->save();

		return array('status' => 'OK', 'result' => $annotation);
	}

	public function ajaxDestroy($id)
	{
		$annotation = Annotation::find($id);

		if (!$annotation)
			return array('status' => 'ERROR', 'error' => 'Annotation not found.');

		$annotation->delete();

		return array('status' => 'OK');
	}

	public function ajaxUploadLogo(Request $request, $id)
	{
		$annotation = Annotation::find($id);

		if (!$annotation)
			return array('status' => 'ERROR', 'error' => 'Annotation not found.');

		if ($request->hasFile('image')) {
		    $ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'annotations' . $ds . $id . $ds . 'image';

			if ($request->file('image')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('image');
			    $filename = uniqid() . '.' . $file->guessClientExtension();

		    	if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
		    		$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

		    		$annotation->logo = $pseudoFile;
		    		$annotation->save();	    

					return array('status' => 'OK', 'result' => $annotation);
			    }
			    else {
			    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading logo.');
		    	}
		    }
		    else {
		    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading logo.');
	    	}
		}
		else {
			return array('status' => 'ERROR', 'error' => 'Error encountered while uploading logo.');
		}
	}

	public function ajaxImportAnnotations(Request $request)
	{
		$now = date('Y-m-d H:i:s');

		if ($request->hasFile('file-upload')) {
			$timestamp = date('Y_m_d_His');

	    	$ds = DIRECTORY_SEPARATOR;
	    	$filename = $request->filename;
			$storeFolder = 'files' . $ds . 'persons' . $ds . Auth::id() . $ds . $timestamp;

			if ($request->file('file-upload')->isValid()) {
				if(!is_dir($storeFolder)) {
			      	mkdir($storeFolder, 0755, TRUE);
			    } 

			    $file = $request->file('file-upload');
			    $filename = uniqid() . '.' . $file->guessClientExtension();

		    	if ($targetFile = $file->move($storeFolder, $filename)) {
		    		$status = 'OK';
		    		$message = '';

		    		$annotations = array();

		    		Excel::selectSheetsByIndex(0)->load(public_path($targetFile), function($reader) use (&$annotations, &$status, &$message) {
						$row_count = 1;

						foreach ($reader->get() as $row) {
							$row_count++;
							$floor = array();

							if (!$row->name || $row->name == '') {
								$status = 'INVALID';
								$message = 'Name column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->subcategory || $row->subcategory == '') {
								$status = 'INVALID';
								$message = 'Sub Category column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->longitude || $row->longitude == '') {
								$status = 'INVALID';
								$message = 'Longitude column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->latitude || $row->latitude == '') {
								$status = 'INVALID';
								$message = 'Latitude column at row ' . $row_count . ' is invalid.';
							}

							$sub_category = SubCategory::where('name', $row->subcategory)->first();

							if (!$sub_category) {
								$status = 'INVALID';
								$message = 'Sub Category column at row ' . $row_count . ' does not exist.';	
							}

							$annotation['name'] = $row->name ? $row->name : '';
							$annotation['longitude'] = $row->longitude ? $row->longitude : '';
							$annotation['latitude'] = $row->latitude ? $row->latitude : '';
							$annotation['subcategoryid'] = $sub_category->id;

							$annotations[] = $annotation;
						}
					}, 'ISO-8859-1');

					if ($status == 'INVALID')
						return array('status' => $status, 'error' => $message);

					foreach ($annotations as $annotation) {
						$anno = new Annotation;

						$anno->name = $annotation['name'];
						$anno->longitude = $annotation['longitude'];
						$anno->latitude = $annotation['latitude'];
						$anno->sub_category_id = $annotation['subcategoryid'];
						$anno->floor_id = $request->floor_id;
						$anno->save();
					}

					return array('status' => 'OK');
			    }
			    else {
			    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading file.');
		    	}
		    }
		    else {
		    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading file.');
	    	}
		}
		else {
			return array('status' => 'ERROR', 'error' => 'Error encountered while uploading file.');
		}
	}
}
