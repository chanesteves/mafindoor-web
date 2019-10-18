<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Session;
use DB;
use View;

use App\User;
use App\Building;
use App\Annotation;
use App\Image;
use App\Activity;
use App\Point;
use App\Adjascent;
use App\PathFinder;

use App\AStar\Graph\Link;
use App\AStar\Graph\Graph;
use App\AStar\Graph\MNode;
use App\AStar\Graph\MAStar;
use App\AStar\Graph\SequencePrinter;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BuildingsController extends Controller
{
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public static function show(Request $request, $id)
	{
		$user = User::find($request->user_id);;

		if (!$user)
			$user = Auth::user();

		$building = Building::find($id);

		if (!$building)
			return redirect('/');

		$activity = new Activity;

		if ($user)
			$activity->user_id = $user->id;
		$activity->object_id = $building->id;
		$activity->object_type = get_class($building);
		$activity->request_path = \Request::getRequestUri();
		$activity->request_type = 'view';

		if (strpos(\Request::getRequestUri(), 'api/') !== false)
			$activity->request_via = 'mobile';
		else
			$activity->request_via = 'web';

		$activity->save();

		return View::make('buildings.show')->with(array(
														'page' => 'Show Building', 
														'building' => $building

													)
												);
	}

	public function createAllSlugs () {
		$buildings = Building::all();

		foreach ($buildings as $building) {
			$slug = str_slug($building->name);
			$build = Building::where('slug', $slug)->first();
			$count = 0;

			while ($build && $building->id != $build->id) {
				$count++;
				$slug = str_slug($building->name . $count);
				$build = Building::where('slug', $slug)->first();
			}

			$building->slug = str_slug($slug);
			
			$building->save();
		}

		return 'DONE!!!';
	}

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

	public function showRoutes (Request $request, $id) {
		$origin = Annotation::find($request->origin);
		$destination = Annotation::find($request->destination);

		if (!$origin)
			return array('status' => 'ERROR', 'error' => 'Invalid origin.');

		if (!$destination)
			return array('status' => 'ERROR', 'error' => 'Invalid destination.');

		$origin_entry = $origin->entries()->first();
		$destination_entry = $destination->entries()->first();

		if (!$origin_entry)
			return array('status' => 'ERROR', 'error' => 'Origin has no entry point.');

		if (!$destination_entry)
			return array('status' => 'ERROR', 'error' => 'Destination has no entry point.');
		
		return $this->getRoute($id, $origin_entry->point, $destination_entry->point);
	}

	public function getRoute ($id, $from, $to) {
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Building not found.');

		$links = [];
		foreach ($building->adjascents as $adjascent) {
			if ($adjascent->origin && $adjascent->destination) {
				if (($from->floor_id == $to->floor_id && $adjascent->origin->floor_id == $adjascent->destination->floor_id)
					|| $from->floor_id != $to->floor_id)
				$links[] = new Link(new MNode($adjascent->origin->longitude, $adjascent->origin->latitude, $adjascent->origin->floor_id), 
									new MNode($adjascent->destination->longitude, $adjascent->destination->latitude, $adjascent->destination->floor_id), 
									$adjascent->distance);
			}
		}

		$graph = new Graph($links);

		$start = new MNode($from->longitude, $from->latitude, $from->floor_id);
		$goal = new MNode($to->longitude, $to->latitude, $to->floor_id);

		$aStar = new MAStar($graph);
		$solution = $aStar->run($start, $goal);

		$printer = new SequencePrinter($graph, $solution);

		$sequence = $printer->getSequence();

		$floors = [];
		$distance = $printer->getTotalDistance();
		foreach ($sequence as $node) {
			$point = Point::with('floor')->where(array('longitude' => $node->getX(), 'latitude' => $node->getY(), 'floor_id' => $node->getF()))->first();

			if ($point) {
				if (!isset($floors[$point->floor_id]))
					$floors[$point->floor_id] = array("points" => [], 'floor_id' => $point->floor_id, 'floor_label' : $point->floor ? $point->floor->label : '');

				$floors[$point->floor_id]["points"][] = $point;
			}
		}

		return array( 'status' => 'OK', 'floors' => $floors, 'distance' => $distance);
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

		$slug = str_slug($building->name);
		$build = Building::where('slug', $slug)->first();
		$count = 0;

		while ($build && $building->id != $build->id) {
			$count++;
			$slug = str_slug($building->name . $count);
			$build = Building::where('slug', $slug)->first();
		}

		$building->slug = str_slug($slug);
		
		$building->save();

		return array('status' => 'OK', 'result' => $building);
	}

	public function ajaxShow(Request $request, $id) {
		$user = null;

    	if ($request->api_token && $request->api_token != '')
    		$user = User::where('api_token', $request->api_token)->first();

		if (!$user)
			$user = Auth::user();

		$building = Building::with('images', 'floors', 'floors.annotations', 'floors.annotations.sub_category', 'floors.annotations.sub_category.user_searches', 'floors.annotations.sub_category.category', 'floors.annotations.floor')->find($id);

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

		$slug = str_slug($building->name);
		$build = Building::where('slug', $slug)->first();
		$count = 0;

		while ($build && $building->id != $build->id) {
			$count++;
			$slug = str_slug($building->name . $count);
			$build = Building::where('slug', $slug)->first();
		}

		$building->slug = str_slug($slug);

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
		$distance = 'NULL';
		$buildings = Building::with('floors')->whereNotNull('name');

		if ($request->lng && $request->lat) {
			$distance = 'SQRT(POW(floors.longitude - ' . $request->lng . ', 2) + POW(floors.latitude - ' . $request->lat . ', 2))';

			$buildings = Building::with('floors')
									->leftJoin('floors', function ($q) {
										$q->on('floors.building_id', 'buildings.id');
										$q->where('floors.label', 'G');
										$q->whereNull('floors.deleted_at');
									})->orderByRaw($distance);
		}

		if ($request->user_id)
			$buildings = $buildings->select(DB::raw('buildings.*, IFNULL(buildings.unlocked_at, user_buildings.unlocked_at) as unlocked_at, ' . $distance . ' as distance'))
								->leftJoin('user_buildings', function ($q) use ($request) {
									$q->on('user_buildings.building_id', 'buildings.id');
									$q->where('user_buildings.user_id', $request->user_id);
									$q->whereNull('user_buildings.deleted_at');
								})->where('buildings.status', 'live');
		else
			$buildings = $buildings->select(DB::raw(DB::raw('buildings.*, ' . $distance . ' as distance')))->where('buildings.status', 'live');

		return array('status' => 'OK', 'buildings' => $buildings->limit(5)->get(), 'current_building' => $buildings->havingRaw('IFNULL(distance, 100) <= buildings.max_radius')->first());	
	}

	public function ajaxSearch (Request $request) {
		$str = preg_replace('/(^|&)\+[^&]*/', ' ', $request->query->get('query'));
		$str = preg_replace('/ +/', '%', $str);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
		$buildings = Building::selectRaw("id, slug, logo as image, name as value, address as description, 'building' as type, NULL as floor_id")
							->whereRaw('CONCAT(name, address) LIKE \'%' . $str . '%\' AND status = \'live\'')
							->orWhereRaw('CONCAT(address, name) LIKE \'%' . $str . '%\' AND status = \'live\'')
							->get();

		 if ($str != '') {
			$annotations = Annotation::selectRaw("annotations.id, annotations.slug, annotations.logo as image, annotations.name as value, '' as description, 'annotation' as type, floor_id")
								->with('floor')
								->join('floors', 'floors.id', 'floor_id')
								->join('buildings', 'buildings.id', 'building_id')
								->whereRaw('CONCAT(annotations.name, buildings.name) LIKE \'%' . $str . '%\'')
								->orWhereRaw('CONCAT(buildings.name, annotations.name) LIKE \'%' . $str . '%\'')
								->get();

			 foreach ($annotations as $annotation) {
			 	if (!$annotation->image || $annotation->image == '')
			 		$annotation->image = $annotation->sub_category ? $annotation->sub_category->icon : '';

			 	if (!$annotation->image || $annotation->image == '')
			 		$annotation->image = $annotation->sub_category && $annotation->sub_category->category ? $annotation->sub_category->category->icon : '';

			 	$annotation->description = $annotation->floor && $annotation->floor->building ? $annotation->floor->building->name . ', ' . $annotation->floor->name : '';
			 }

			 $result = $annotations->merge($buildings);
		}
		else {
			$result = $buildings;
		}

		$result = $result->take(5);

		 return array('status' => 'OK', 'result' => $result->toArray(), 'suggestions' => $result);
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

	public function ajaxUploadImages(Request $request, $id)
	{
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Building not found.');

		if ($request->hasFile('file')) {
			$ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'buildings' . $ds . $id . $ds . 'photos';

			if ($request->file('file')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('file');
			    $filename = uniqid() . '.' . $file->guessClientExtension();
			    
				if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
		    		$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

					$image = new Image;
					$image->url = $pseudoFile;
					$image->save();

		    		return array('status' => 'OK', 'image' => $image);
			    }
			    else {
			    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading images.');
		    	}
			}
			else {
		    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading images.');
	    	}
		}
		else {
			return array('status' => 'ERROR', 'error' => 'Error encountered while uploading images.');
		}
	}

	public function ajaxStoreImages (Request $request, $id) {
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Building not found.');

		$images = array();

		if ($request->images) 
			$images = $request->images;

		$building->images()->sync($images);
		$building->save();

		return array('status' => 'OK', 'building' => $building);
	}

	public function ajaxUploadImage(Request $request, $id)
	{
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Building not found.');

		if ($request->hasFile('file')) {
			$ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'buildings' . $ds . $id . $ds . 'image';

			if ($request->file('file')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('file');
			    $filename = uniqid() . '.' . $file->guessClientExtension();
			    
				if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
		    		$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

		    		return array('status' => 'OK', 'path' => $pseudoFile);
			    }
			    else {
			    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading images.');
		    	}
			}
			else {
		    	return array('status' => 'ERROR', 'error' => 'Error encountered while uploading images.');
	    	}
		}
		else {
			return array('status' => 'ERROR', 'error' => 'Error encountered while uploading images.');
		}
	}

	public function ajaxStoreImage (Request $request, $id) {
		$building = Building::find($id);

		if (!$building)
			return array('status' => 'ERROR', 'error' => 'Building not found.');

		$building->image = $request->image;
		$building->save();

		return array('status' => 'OK', 'building' => $building);
	}

	public function ajaxUpdateAdjascents(Request $request, $id)
	{
	    $this->validate($request, [
	      'adjascents' => 'required'
	    ]);

	    $building = Building::find($id);

	    if (!$building)
	      return array('status' => 'ERROR', 'error' => 'Building not found.');

	    $adjascent_ids = [];
	    $adjascents = json_decode($request->adjascents);

	    foreach ($adjascents as $a) {
	    	$origin = Point::find($a->origin);
	    	$destination = Point::find($a->destination);

	    	if (!$origin || !$destination)
	    		return;

	    	$distance = sqrt(pow($origin->longitude - $destination->longitude, 2) + pow($origin->latitude - $destination->latitude, 2));
			
	    	if ($origin->floor_id != $destination->floor_id)
				$distance += 0.001;

	    	$adjascent = null;

	    	if (isset($a->id))
	      		$adjascent = Adjascent::find($a->id);

		    if (!$adjascent)
		    	 $adjascent = Adjascent::where('origin_id', $a->origin)->where('destination_id', $a->destination)->first();

		      if (!$adjascent)
		        $adjascent = new Adjascent;

		      $adjascent->origin_id = $a->origin;
		      $adjascent->destination_id = $a->destination;
		      $adjascent->distance = $distance;
		      $adjascent->building_id = $building->id;	      
		      $adjascent->save();

		      $adjascent_ids[] = $adjascent->id;

		      if ($a->two_way) {
		      	$reverse_adjascent = null;

		      	if (!$reverse_adjascent)
		    	 	$reverse_adjascent = Adjascent::where('origin_id', $a->destination)->where('destination_id', $a->origin)->first();

			      if (!$reverse_adjascent)
			        $reverse_adjascent = new Adjascent;

			      $reverse_adjascent->origin_id = $a->destination;
			      $reverse_adjascent->destination_id = $a->origin;
			      $reverse_adjascent->distance = $distance;
			      $reverse_adjascent->building_id = $building->id;
			      $reverse_adjascent->save();

			      $adjascent_ids[] = $reverse_adjascent->id;
	      	  }
	    }

	    Adjascent::where('building_id', $building->id)->whereNotIn('id', $adjascent_ids)->delete();

	    return array('status' => 'OK', 'result' => $building, 'adjascents' => $adjascents);
	}

	public function ajaxShowRoutes (Request $request, $id) {
		$origin = Annotation::find($request->origin);
		$destination = Annotation::find($request->destination);

		if (!$origin)
			return array('status' => 'ERROR', 'error' => 'Invalid origin.');

		if (!$destination)
			return array('status' => 'ERROR', 'error' => 'Invalid destination.');

		$origin_entry = $origin->entries()->first();
		$destination_entry = $destination->entries()->first();

		if (!$origin_entry)
			return array('status' => 'ERROR', 'error' => 'Origin has no entry point.');

		if (!$destination_entry)
			return array('status' => 'ERROR', 'error' => 'Destination has no entry point.');
		
		return $this->getRoute($id, $origin_entry->point, $destination_entry->point);
	}
}
