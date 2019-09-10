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
use App\Activity;

use Carbon\Carbon;

class PagesController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth')->except('search', 'welcome', 'privacy');
	}

	public function welcome(Request $request) {
		$buildings = Building::where('status', 'live')->where('image', '!=', '')->get();

		return View::make('welcome')->with(array(
													'page' => 'Welcome',
													'buildings' => $buildings
												));
	}

	public function privacy(Request $request) {
		return View::make('privacy')->with(array(
													'page' => 'Privacy'
												));
	}

	public function dashboard(Request $request) {
		$counts['users'] = User::count();
		$counts['venues'] = Building::where('status', 'Live')->count();
		$counts['searches_this_month'] = Activity::where('request_type', 'search')->where('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())->count();

		$searches_by_venue = Activity::selectRaw("buildings.name AS label, request_via AS type, COUNT(*) as total")
										->join('buildings', function ($q) {
											$q->on('activities.object_id', 'buildings.id');
											$q->where('activities.object_type', 'App\\Building');
										})
										->groupBy('label')
										->groupBy('type')
										->orderBy('total', 'desc')
										->limit(10)
										->get();

		$searches_by_platform = Activity::selectRaw("request_via AS label, COUNT(*) AS data")
										->whereNotNull('request_via')
										->where('request_via', '!=', '')
										->groupBy('label')
										->get();

		$most_searched_places = Activity::selectRaw("annotations.name AS place, annotations.logo as image, annotations.floor_id, annotations.sub_category_id, annotations.sub_category_id, COUNT(*) AS searches")
										->join('annotations', function ($q) {
											$q->on('activities.object_id', 'annotations.id');
											$q->where('activities.object_type', 'App\\Annotation');
										})
										->groupBy('place')
										->groupBy('logo')
										->groupBy('floor_id')
										->groupBy('sub_category_id')
										->orderBy('searches', 'desc')
										->limit(8)
										->get();

		foreach ($most_searched_places as $most_searched_place) {
			$most_searched_place->floor = Floor::find($most_searched_place->floor_id);
			$most_searched_place->floor->building = $most_searched_place->floor ? $most_searched_place->floor->building : null;

			$most_searched_place->sub_category = SubCategory::find($most_searched_place->sub_category_id);
			$most_searched_place->sub_category->category = $most_searched_place->sub_category ? $most_searched_place->sub_category->category : null;

			$image = $most_searched_place->image;

			if (!$image || trim($image) == '')
				$image = $most_searched_place->sub_category ? $most_searched_place->sub_category->icon : '';

			if (!$image || trim($image) == '')
				$image = $most_searched_place->sub_category && $most_searched_place->sub_category->category ? $most_searched_place->sub_category->category->icon : '';

			if (!$image || trim($image) == '')
				$image = '/img/avatars/initials/' . substr($most_searched_place->name, 0, 1) . '.png';

			$most_searched_place->image = $image;
		}

		$most_active_users = User::selectRaw("CONCAT(people.first_name, ' ', people.last_name) AS name, people.image, COUNT(*) AS activities")
										->leftJoin('activities', function ($q) {
											$q->on('activities.user_id', 'users.id');
										})
										->join('people', function ($q) {
											$q->on('people.id', 'users.id');
										})
										->groupBy('name')
										->groupBy('image')
										->orderBy('activities', 'desc')
										->limit(5)
										->get();

		foreach ($most_active_users as $most_active_user) {
			$image = $most_active_user->image;

			if (!$image || trim($image) == '')
				$image = '/img/avatars/initials/' . substr($most_active_user->name, 0, 1) . '.png';

			$most_active_user->image = $image;
		}

		$recent_traffic = Activity::selectRaw("DATE(created_at) AS dt, COUNT(*) AS traffic")
										->groupBy('dt')
										->orderBy('dt', 'desc')
										->limit(30)
										->get();

		return View::make('dashboard')->with(array(
													'page' => 'Dashboard',
													'counts' => $counts,
													'searches_by_venue' => $searches_by_venue,
													'searches_by_platform' => $searches_by_platform,
													'most_searched_places' => $most_searched_places,
													'most_active_users' => $most_active_users,
													'recent_traffic' => $recent_traffic
												));
	}

	public function search(Request $request) {
		$user = Auth::user();

		$distance = 'NULL';
		$buildings = Building::whereNotNull('name');

		if ($request->header && $request->header == 'no')
			Session::put('header', 'no');

		if ($request->sidebar && $request->sidebar == 'no')
			Session::put('sidebar', 'no');

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
		
		if ($search_building) {
			$activity = new Activity;

			if ($user)
				$activity->user_id = $user->id;
			$activity->object_id = $search_building->id;
			$activity->object_type = get_class($search_building);
			$activity->request_path = \Request::getRequestUri();
			$activity->request_type = 'search';

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$activity->request_via = 'mobile';
			else
				$activity->request_via = 'web';

			$activity->save();
		}

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

		if ($search_annotation) {
			$activity = new Activity;

			if ($user)
				$activity->user_id = $user->id;
			$activity->object_id = $search_annotation->id;
			$activity->object_type = get_class($search_annotation);
			$activity->request_path = \Request::getRequestUri();
			$activity->request_type = 'search';

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$activity->request_via = 'mobile';
			else
				$activity->request_via = 'web';

			$activity->save();
		}

		$log_sub_category = $search_sub_category;

		if (!$log_sub_category && $search_annotation)
			$log_sub_category = $search_annotation->sub_category;

		if ($log_sub_category) {
			$activity = new Activity;

			if ($user)
				$activity->user_id = $user->id;
			$activity->object_id = $log_sub_category->id;
			$activity->object_type = get_class($log_sub_category);
			$activity->request_path = \Request::getRequestUri();
			$activity->request_type = 'search';

			if (strpos(\Request::getRequestUri(), 'api/') !== false)
				$activity->request_via = 'mobile';
			else
				$activity->request_via = 'web';

			$activity->save();

			$log_category = $log_sub_category->category;

			if ($log_category) {
				$activity = new Activity;

				if ($user)
					$activity->user_id = $user->id;
				$activity->object_id = $log_category->id;
				$activity->object_type = get_class($log_category);
				$activity->request_path = \Request::getRequestUri();
				$activity->request_type = 'search';

				if (strpos(\Request::getRequestUri(), 'api/') !== false)
					$activity->request_via = 'mobile';
				else
					$activity->request_via = 'web';
			
				$activity->save();
			}
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
