<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;

use App\Route;
use App\Turn;
use App\Building;
use App\Floor;
use App\Annotation;
use App\Entry;
use App\Point;

use App\Http\Controllers\BuildingsController;

use Illuminate\Console\Command;

class CreateRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to create a route';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deleted_building_ids = Building::withTrashed()
                                        ->where('deleted_at', '>', 
                                            Carbon::now()->subMinutes(1)->toDateTimeString()
                                        )->pluck('id')->toArray();
        Floor::whereIn('building_id', $deleted_building_ids)->delete();
        print_r("deleted buildings count: " . count($deleted_building_ids) . "\n");

        $deleted_floor_ids = Floor::withTrashed()
                                    ->where('deleted_at', '>', 
                                        Carbon::now()->subMinutes(1)->toDateTimeString()
                                    )->pluck('id')->toArray();
        Annotation::whereIn('floor_id', $deleted_floor_ids)->delete();
        print_r("deleted floors count: " . count($deleted_floor_ids) . "\n");

        $deleted_annotation_ids = Floor::withTrashed()
                                        ->where('deleted_at', '>', 
                                            Carbon::now()->subMinutes(1)->toDateTimeString()
                                        )->pluck('id')->toArray();
        Entry::whereIn('annotation_id', $deleted_annotation_ids)->delete();
        print_r("deleted annotations count: " . count($deleted_annotation_ids) . "\n");

        $deleted_entries = Entry::withTrashed()
                                ->where('deleted_at', '>', 
                                    Carbon::now()->subMinutes(1)->toDateTimeString()
                                )->get();
        print_r("deleted entries count: " . $deleted_entries->count() . "\n");
        foreach ($deleted_entries as $deleted_entry) {
            if ($deleted_entry->point)
                $deleted_entry->point->delete();
        }

        $deleted_point_ids = Point::withTrashed()
                                    ->where('deleted_at', '>', 
                                        Carbon::now()->subMinutes(1)->toDateTimeString()
                                    )->pluck('id')->toArray();
        print_r("deleted points count: " . count($deleted_point_ids) . "\n");

        $routes_w_turn_ids = Route::join('turns', 'routes.id', 'route_id')->pluck('routes.id')->toArray();
        Route::whereNotIn('id', $routes_w_turn_ids)->delete();

        $routes = Route::whereIn('turns.point_id', $deleted_point_ids)
                        ->join('turns', 'routes.id', 'route_id')
                        ->get();

        foreach ($routes as $route) {
            if ($route->turns->count() == 0) {
                $route->delete();
                continue;
            }

            foreach ($route->turns as $turn) {
                if (!$turn->point) {
                    $turn->delete();
                    $route->delete();
                    break;
                }
            }
        }

        $deleted_turn_ids = Turn::withTrashed()
                                ->where('deleted_at', '>', 
                                    Carbon::now()->subMinutes(1)->toDateTimeString()
                                )->pluck('id')->toArray();
        print_r("deleted turns count: " . count($deleted_turn_ids) . "\n");

        $deleted_route_ids = Turn::withTrashed()
                                    ->where('deleted_at', '>', 
                                        Carbon::now()->subMinutes(1)->toDateTimeString()
                                    )->pluck('id')->toArray();
        print_r("deleted routes count: " . count($deleted_route_ids) . "\n");

        $routed_point_ids = [];

        $origin_point = null;
        $routed_annotation_ids = [];
        $unrouted_annotation_ids = [];
        $unrouted_annotations = collect();
        do {
            $origin_point = Point::whereNotIn('id', $routed_point_ids)->first();

            if (!$origin_point)
                break;

            $origin_annotation = Annotation::select('annotations.id')
                                            ->where('entries.point_id', $origin_point->id)
                                            ->join('entries', 'annotations.id', 'annotation_id')
                                            ->first();

            $routed_annotation_ids = Route::where('origin_point_id', $origin_point->id)
                                            ->join('points', 'points.id', 'destination_point_id')
                                            ->join('entries', 'points.id', 'point_id')
                                            ->pluck('annotation_id')->toArray();
            
            if ($origin_annotation)
                $routed_annotation_ids[] = $origin_annotation->id;

            DB::enableQueryLog();

            $unrouted_annotations = Annotation::select(DB::raw('annotations.*'))
                                                ->join('floors', 'floors.id', 'floor_id')
                                                ->join('buildings', 'buildings.id', 'building_id')
                                                ->whereNotIn('annotations.id', $routed_annotation_ids)
                                                ->where('building_id', $origin_point->floor->building_id)
                                                ->get();

            if ($unrouted_annotations->count() == 0)
                $routed_point_ids[] = $origin_point->id;
        } while ($unrouted_annotations->count() == 0);

        if (!$origin_point) {
            print_r("no points left to process!!!");
            return;
        }

        print_r("origin point id: " . $origin_point->id . "\n");
        print_r("routed annotations count: " . count($routed_annotation_ids) . "\n");
        print_r("unrouted annotations count: " . $unrouted_annotations->count() . "\n");

        $destination_annotation = $unrouted_annotations->first();
        print_r("destination annotation id: " . $destination_annotation->id . "\n");

        $destination_entry = null;
        $min_entries_distance = 100;

        foreach ($destination_annotation->entries as $d_entry) {
            $distance = sqrt(pow($origin_point->longitude - $d_entry->point->longitude, 2) + pow($origin_point->latitude - $d_entry->point->latitude, 2));

            if ($distance < $min_entries_distance) {
                $min_entries_distance = $distance;
                $destination_entry = $d_entry;
            }
        }

        print_r("destination entry id: " . $destination_entry->id . "\n");
        print_r("destination point id: " . $destination_entry->point->id . "\n");

        $result = BuildingsController::getRoute($destination_annotation->floor->building_id, $origin_point, $destination_entry->point);

        if ($result['status'] == 'ERROR') {
            print_r("error encountered while getting route!!!");
            return;
        }

        $route = Route::where(array('origin_point_id' => $origin_point->id, 'destination_point_id' => $destination_entry->point->id))->first();

        if (!$route) {
            $route = new Route;

            $route->origin_point_id = $origin_point->id;
            $route->destination_point_id = $destination_entry->point->id;
            $route->save();
        }  

        $step = 0;
        foreach ($result['floors'] as $key => $value) {
            foreach ($value['points'] as $point) {
                $turn = Turn::where(array('point_id' => $point->id, 'route_id' => $route->id))->first();

                if (!$turn) {
                    $turn = new Turn;

                    $turn->point_id = $point->id;
                    $turn->route_id = $route->id;
                }

                $turn->step = $step;
                $turn->save();

                $step++;
            }
        }

        print_r("route saved!!!\n");
        print_r("route id: " . $route->id . "\n");
        print_r("steps count: " . $step . "\n");
    }
}
