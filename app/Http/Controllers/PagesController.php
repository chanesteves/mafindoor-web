<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Session;
use View;
use DB;
use Config;

use App\Building;
use App\Floor;
use App\Annotation;
use App\Category;
use App\SubCategory;

class PagesController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	// --------------------------
	// ---- Directories ---------
	// --------------------------

	public function venues() {
		$buildings = Building::all();

		return View::make('directories.venues')->with(array(
															'page' => 'Venues',
															'buildings' => $buildings
														));
	}

	public function floors(Request $request) {
		$buildings = Building::all();
		$building = null;

		if ($request->building_id)
			$building = Building::find($request->building_id);

		if (!$building)
			$building = Building::first();

		$floors = collect();

		if ($building)
			$floors = Floor::where('building_id', $building->id)->get();

		return View::make('directories.floors')->with(array(
															'page' => 'Floors',
															'building' => $building,
															'buildings' => $buildings,
															'floors' => $floors
														));
	}

	public function annotations(Request $request) {
		$buildings = Building::all();
		$building = null;

		if ($request->building_id)
			$building = Building::find($request->building_id);

		if (!$building)
			$building = Building::first();

		$floors = Floor::all();
		$floor = null;

		if ($request->floor_id)
			$floor = Floor::find($request->floor_id);

		if (!$floor) {
			if ($building)
				$floor = Floor::where('building_id', $building->id)->first();
			else
				$floor = Floor::first();
		}

		$annotations = Annotation::where('floor_id', $floor->id)->get();

		$categories = Category::all();

		return View::make('directories.annotations')->with(array(
															'page' => 'Annotations',
															'building' => $building,
															'floor' => $floor,
															'buildings' => $buildings,
															'floors' => $floors,
															'annotations' => $annotations,
															'categories' => $categories
														));
	}

	// --------------------------
	// ---- Maintenance ---------
	// --------------------------

	public function categories(Request $request) {
		$categories = Category::all();
		$category = null;

		if ($request->category_id)
			$category = Category::find($request->category_id);

		if (!$category)
			$category = Category::first();

		$sub_categories = SubCategory::where('category_id', $category->id)->get();

		return View::make('maintenance.categories')->with(array(
															'page' => 'Categories',
															'categories' => $categories,
															'category' => $category,
															'sub_categories' => $sub_categories
														));
	}
}
