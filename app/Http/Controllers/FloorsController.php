<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Session;

use App\User;
use App\Building;
use App\Floor;
use App\Activity;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FloorsController extends Controller
{
	public function createAllSlugs () {
		$floors = Floor::all();

		foreach ($floors as $floor) {
			$slug = str_slug($floor->name);
			$flo = Floor::where('slug', $slug)->first();
			$count = 0;

			while ($flo && $floor->id != $flo->id) {
				$count++;
				$slug = str_slug($floor->name . $count);
				$flo = Floor::where('slug', $slug)->first();
			}

			$floor->slug = str_slug($slug);
			
			$floor->save();
		}

		return 'DONE!!!';
	}

	public function exportFloors (Request $request) {
		$building = Building::find($request->building_id);

		$floors = Floor::where('building_id', $building->id)->get();

		$cells = array();

		$cells[] = array('column' => 'A', 'row' => 1, 'value' => 'Name');
		$cells[] = array('column' => 'B', 'row' => 1, 'value' => 'Label');
		$cells[] = array('column' => 'C', 'row' => 1, 'value' => 'Map URL');
		$cells[] = array('column' => 'D', 'row' => 1, 'value' => 'Longitude');
		$cells[] = array('column' => 'E', 'row' => 1, 'value' => 'Latitude');
		$cells[] = array('column' => 'F', 'row' => 1, 'value' => 'Initial Zoom');
		$cells[] = array('column' => 'G', 'row' => 1, 'value' => 'Minimum Zoom');
		$cells[] = array('column' => 'H', 'row' => 1, 'value' => 'Maximum Zoom');
		$cells[] = array('column' => 'I', 'row' => 1, 'value' => 'Status');

		$row = 2;

		foreach ($floors as $floor) {
			$cells[] = array('column' => 'A', 'row' => $row, 'value' => $floor->name);
			$cells[] = array('column' => 'B', 'row' => $row, 'value' => $floor->label);
			$cells[] = array('column' => 'C', 'row' => $row, 'value' => $floor->map_url);
			$cells[] = array('column' => 'D', 'row' => $row, 'value' => $floor->longitude);
			$cells[] = array('column' => 'E', 'row' => $row, 'value' => $floor->latitude);
			$cells[] = array('column' => 'F', 'row' => $row, 'value' => $floor->zoom);
			$cells[] = array('column' => 'G', 'row' => $row, 'value' => $floor->min_zoom);
			$cells[] = array('column' => 'H', 'row' => $row, 'value' => $floor->max_zoom);
			$cells[] = array('column' => 'I', 'row' => $row, 'value' => $floor->status);

			$row++;
		}

    	return Excel::create('floors', function($excel) use ($cells, $building) {
		    $excel->sheet($building->name, function($sheet) use ($cells) {		    	
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
			'label' => 'required',
			'map_url' => 'required',
			'longitude' => 'required',
			'latitude' => 'required',
			'zoom' => 'required',
			'min_zoom' => 'required',
			'max_zoom' => 'required',
			'status' => 'required',
			'building_id' => 'required'
		]);

		$floor = Floor::where(array('name' => $request->name, 'building_id' => $request->building_id))->first();

		if ($floor)
			return array('status' => 'ERROR', 'error' => '\'' . $request->name . '\' already exists.');

		$floor = new Floor;

		$floor->name = $request->name;
		$floor->label = $request->label;
		$floor->map_url = $request->map_url;
		$floor->longitude = $request->longitude;
		$floor->latitude = $request->latitude;
		$floor->zoom = $request->zoom;
		$floor->min_zoom = $request->min_zoom;
		$floor->max_zoom = $request->max_zoom;
		$floor->status = $request->status;
		$floor->building_id = $request->building_id;
		$floor->creator_id = Auth::id();
		$floor->save();

		$slug = str_slug($floor->name);
		$flo = Floor::where('slug', $slug)->first();
		$count = 0;

		while ($flo && $floor->id != $flo->id) {
			$count++;
			$slug = str_slug($floor->name . $count);
			$flo = Floor::where('slug', $slug)->first();
		}

		$floor->slug = str_slug($slug);
		
		$floor->save();

		return array('status' => 'OK', 'result' => $floor);
	}

	public function ajaxShow(Request $request, $id) {
		$user = null;

    	if ($request->api_key && $request->api_key != '')
    		$user = User::where('api_key', $request->api_key)->first();

		if (!$user)
			$user = Auth::user();

		$floor = Floor::with('building', 'building.floors', 'building.floors.annotations', 'building.floors.annotations.floor', 'building.floors.annotations.floor.building', 'building.floors.annotations.sub_category', 'building.floors.annotations.sub_category.category', 'annotations', 'annotations.sub_category', 'annotations.sub_category.category')->find($id);

		$building = $floor->building;

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

		return array('status' => 'OK', 'floor' => $floor);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required',
			'label' => 'required',
			'map_url' => 'required',
			'longitude' => 'required',
			'latitude' => 'required',
			'zoom' => 'required',
			'min_zoom' => 'required',
			'max_zoom' => 'required',
			'status' => 'required',
			'building_id' => 'required'
		]);

		$floor = Floor::find($id);

		if (!$floor)
			return array('status' => 'ERROR', 'error' => 'Floor not found.');

		$floor->name = $request->name;
		$floor->label = $request->label;
		$floor->map_url = $request->map_url;
		$floor->longitude = $request->longitude;
		$floor->latitude = $request->latitude;
		$floor->zoom = $request->zoom;
		$floor->min_zoom = $request->min_zoom;
		$floor->max_zoom = $request->max_zoom;
		$floor->status = $request->status;
		$floor->building_id = $request->building_id;
		$floor->creator_id = Auth::id();
		$floor->save();

		$slug = str_slug($floor->name);
		$flo = Floor::where('slug', $slug)->first();
		$count = 0;

		while ($flo && $floor->id != $flo->id) {
			$count++;
			$slug = str_slug($floor->name . $count);
			$flo = Floor::where('slug', $slug)->first();
		}

		$floor->slug = str_slug($slug);
		
		$floor->save();

		return array('status' => 'OK', 'result' => $floor);
	}

	public function ajaxDestroy($id)
	{
		$floor = Floor::find($id);

		if (!$floor)
			return array('status' => 'ERROR', 'error' => 'Floor not found.');

		$floor->delete();

		return array('status' => 'OK');
	}

	public function ajaxImportFloors(Request $request)
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

		    		$floors = array();

		    		Excel::selectSheetsByIndex(0)->load(public_path($targetFile), function($reader) use (&$floors, &$status, &$message) {
						$row_count = 1;

						foreach ($reader->get() as $row) {
							$row_count++;
							$floor = array();

							if (!$row->name || $row->name == '') {
								$status = 'INVALID';
								$message = 'Name column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->label || $row->label == '') {
								$status = 'INVALID';
								$message = 'Label column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->mapurl || $row->mapurl == '') {
								$status = 'INVALID';
								$message = 'Map URL column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->longitude || $row->longitude == '') {
								$status = 'INVALID';
								$message = 'Longitude column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->latitude || $row->latitude == '') {
								$status = 'INVALID';
								$message = 'Latitude column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->initialzoom || $row->initialzoom == '') {
								$status = 'INVALID';
								$message = 'Initial Zoom column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->minzoom || $row->minzoom == '') {
								$status = 'INVALID';
								$message = 'Minimum Zoom column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->maxzoom || $row->maxzoom == '') {
								$status = 'INVALID';
								$message = 'Maximum Zoom column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->status || $row->status == '') {
								$status = 'INVALID';
								$message = 'Status column at row ' . $row_count . ' is invalid.';
							}

							$floor['name'] = $row->name ? $row->name : '';
							$floor['label'] = $row->label ? $row->label : '';
							$floor['mapurl'] = $row->mapurl ? $row->mapurl : '';
							$floor['longitude'] = $row->longitude ? $row->longitude : '';
							$floor['latitude'] = $row->latitude ? $row->latitude : '';
							$floor['initialzoom'] = $row->initialzoom ? $row->initialzoom : '';
							$floor['minzoom'] = $row->minzoom ? $row->minzoom : '';
							$floor['maxzoom'] = $row->maxzoom ? $row->maxzoom : '';
							$floor['status'] = $row->status ? $row->status : '';

							$floors[] = $floor;
						}
					}, 'ISO-8859-1');

					if ($status == 'INVALID')
						return array('status' => $status, 'error' => $message);

					foreach ($floors as $floor) {
						$flo = new Floor;

						$flo->name = $floor['name'];
						$flo->label = $floor['label'];
						$flo->map_url = $floor['mapurl'];
						$flo->longitude = $floor['longitude'];
						$flo->latitude = $floor['latitude'];
						$flo->zoom = $floor['initialzoom'];
						$flo->min_zoom = $floor['minzoom'];
						$flo->max_zoom = $floor['maxzoom'];
						$flo->status = $floor['status'];
						$flo->creator_id = Auth::id();
						$flo->building_id = $request->building_id;
						$flo->save();
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
