<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Session;
use DB;

use App\User;
use App\Building;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BuildingsController extends Controller
{
	public function exportBuildings () {
		$buildings = Building::all();

		$cells = array();

		$cells[] = array('column' => 'A', 'row' => 1, 'value' => 'Name');
		$cells[] = array('column' => 'B', 'row' => 1, 'value' => 'Status');


		$row = 2;

		foreach ($buildings as $building) {
			$cells[] = array('column' => 'A', 'row' => $row, 'value' => $building->name);
			$cells[] = array('column' => 'B', 'row' => $row, 'value' => $building->status);

			$row++;
		}

    	return Excel::create('all venues', function($excel) use ($cells) {
		    $excel->sheet('all venues', function($sheet) use ($cells) {		    	
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
			'address' => 'required',
			'status' => 'required'
		]);

		$building = Building::where(array('name' => $request->name))->first();

		if ($building)
			return array('status' => 'ERROR', 'error' => '\'' . $request->name . '\' already exists.');

		$building = new Building;

		$building->name = $request->name;
		$building->address = $request->address;
		$building->status = $request->status;
		$building->creator_id = Auth::id();
		$building->save();

		return array('status' => 'OK', 'result' => $building);
	}

	public function ajaxShow($id) {
		$building = Building::with('floors', 'floors.annotations', 'floors.annotations.sub_category', 'floors.annotations.sub_category.user_searches', 'floors.annotations.sub_category.category', 'floors.annotations.floor')->find($id);

		return array('status' => 'OK', 'building' => $building);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required',
			'address' => 'required',
			'status' => 'required'
		]);

		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Venue not found.');

		$building->name = $request->name;
		$building->address = $request->address;
		$building->status = $request->status;
		$building->creator_id = Auth::id();
		$building->save();

		return array('status' => 'OK', 'result' => $building);
	}

	public function ajaxDestroy($id)
	{
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Venue not found.');

		$building->delete();

		return array('status' => 'OK');
	}

	public function ajaxShowBuildings (Request $request) {
		$buildings = Building::select(DB::raw('buildings.*, IFNULL(buildings.unlocked_at, user_buildings.unlocked_at) as unlocked_at'))
							->leftJoin('user_buildings', function ($q) {
								$q->on('user_buildings.building_id', 'buildings.id');
								$q->whereNull('user_buildings.deleted_at');
							})->leftJoin('users', function ($q) use ($request) {
								$q->on('user_buildings.user_id', 'users.id');
								$q->where('users.id', $request->id);
								$q->whereNull('users.deleted_at');
							})->where('status', 'live')->get();

		return array('status' => 'OK', 'buildings' => $buildings);	
	}

	public function ajaxUploadLogo(Request $request, $id)
	{
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Building not found.');

		if ($request->hasFile('image')) {
		    $ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'buildings' . $ds . $id . $ds . 'image';

			if ($request->file('image')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('image');
			    $filename = uniqid() . '.' . $file->guessClientExtension();

		    	if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
		    		$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

		    		$building->logo = $pseudoFile;
		    		$building->save();	    

					return array('status' => 'OK', 'result' => $building);
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

	public function ajaxImportBuildings(Request $request)
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

		    		$buildings = array();

		    		Excel::selectSheetsByIndex(0)->load(public_path($targetFile), function($reader) use (&$buildings, &$status, &$message) {
						$row_count = 1;

						foreach ($reader->get() as $row) {
							$row_count++;
							$building = array();

							if (!$row->name || $row->name == '') {
								$status = 'INVALID';
								$message = 'Name column at row ' . $row_count . ' is invalid.';
							}

							if (!$row->status || $row->status == '') {
								$status = 'INVALID';
								$message = 'Status column at row ' . $row_count . ' is invalid.';
							}

							$build = Building::where('name', $row->name)->first();

							if ($build) {
								$status = 'INVALID';
								$message = 'Building ' . $row->name . ' at row ' . $row_count . ' is invalid.';
							}

							$building['name'] = $row->name ? $row->name : '';
							$building['status'] = $row->status ? $row->status : '';

							$buildings[] = $building;
						}
					}, 'ISO-8859-1');

					if ($status == 'INVALID')
						return array('status' => $status, 'error' => $message);

					foreach ($buildings as $building) {
						$build = new Building;

						$build->name = $building['name'];
						$build->status = $building['status'];
						$build->creator_id = Auth::id();
						$build->save();
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
