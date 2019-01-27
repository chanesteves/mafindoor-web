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
use App\User;
use App\Role;
use App\Menu;

class PagesController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth')->except('search');
	}

	public function search(Request $request) {
		$user = Auth::user();

		$distance = 'NULL';
		$buildings = Building::whereNotNull('name');

		if ($request->lng && $request->lat) {
			$distance = 'SQRT(POW(floors.longitude - ' . $request->lng . ', 2) + POW(floors.latitude - ' . $request->lat . ', 2))';

			$buildings = Building::leftJoin('floors', function ($q) {
										$q->on('floors.building_id', 'buildings.id');
										$q->where('floors.label', 'G');
										$q->whereNull('floors.deleted_at');
									})->orderByRaw($distance);
		}

		if ($user && $user->id)
			$buildings = $buildings->select(DB::raw('buildings.*, IFNULL(buildings.unlocked_at, user_buildings.unlocked_at) as unlocked_at, ' . $distance . ' as distance'))
								->leftJoin('user_buildings', function ($q) use ($user) {
									$q->on('user_buildings.building_id', 'buildings.id');
									$q->where('user_buildings.user_id', $user->id);
									$q->whereNull('user_buildings.deleted_at');
								})->where('buildings.status', 'live');
		else
			$buildings = $buildings->select(DB::raw(DB::raw('buildings.*, ' . $distance . ' as distance')))->where('buildings.status', 'live');

		$search_building = null;

		if ($request->building && $request->building != '')
			$search_building = Building::whereRaw('CONVERT(id, CHAR(255))="' . $request->building . '"')->orWhere('slug', $request->building)->first();
		
		$search_floor = null;
		
		if ($search_building)
			$search_floor = $search_building->floors->first();

		if ($request->floor && $request->floor != '')
			$search_floor = Floor::whereRaw('CONVERT(id, CHAR(255))="' . $request->floor . '"')->orWhere('slug', $request->floor)->first();

		$search_sub_category = null;

		if ($request->sub_category && $request->sub_category != '')
			$search_sub_category = SubCategory::whereRaw('CONVERT(id, CHAR(255))="' . $request->sub_category . '"')->orWhere('slug', $request->sub_category)->first();

		$search_annotation = null;

		if ($request->annotation && $request->annotation != '') {
			$search_annotation = Annotation::whereRaw('CONVERT(id, CHAR(255))="' . $request->annotation . '"')->orWhere('slug', $request->annotation)->first();

			$distance = 'SQRT(POW(longitude - ' . $search_annotation->longitude . ', 2) + POW(latitude - ' . $search_annotation->latitude . ', 2))';

			$search_annotation->near = Annotation::where('floor_id', $search_floor->id)
												->where('id', '!=', $search_annotation->id)
												->orderByRaw($distance)->limit(3)->get();
		}

		return View::make('search')->with(array(
													'page' => 'Search',
													'buildings' => $buildings->get(),
													'search_building' => $search_building,
													'search_floor' => $search_floor,
													'search_sub_category' => $search_sub_category,
													'search_annotation' => $search_annotation
												));
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

	// --------------------------
	// ---- Permissions ---------
	// --------------------------

	public function users(Request $request) {
		$users = User::all();
		$roles = Role::all();

		return View::make('permissions.users')->with(array(
															'page' => 'Users',
															'users' => $users,
															'roles' => $roles
														));
	}

	public function privileges(Request $request) {
		$roles = Role::all();
		$menus = Menu::all();

		return View::make('permissions.privileges')->with(array(
															'page' => 'Privileges',
															'roles' => $roles,
															'menus' => $menus
														));
	}

	// --------------------
	// ---- Slugs ---------
	// --------------------

	public function slugs ($building_str = '') {
		$building = null;

		if ($building_str && $building_str != '')
			$building = Building::where('slug', $building_str)->first();

		return $building;
	}
}
