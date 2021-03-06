<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;

use App\Route;
use App\NoRoute;
use App\Turn;
use App\Building;
use App\Floor;
use App\Annotation;
use App\Entry;
use App\Point;
use App\Adjascent;
use App\SubCategory;

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
        print_r("start!!!\n");

        $deleted_building_ids = Building::withTrashed()
                                        ->where('deleted_at', '>', 
                                            Carbon::now()->subMinutes(2)->toDateTimeString()
                                        )->pluck('id')->toArray();
        Floor::whereIn('building_id', $deleted_building_ids)->delete();
        print_r("deleted buildings count: " . count($deleted_building_ids) . "\n");

        $deleted_floor_ids = Floor::withTrashed()
                                    ->where('deleted_at', '>', 
                                        Carbon::now()->subMinutes(2)->toDateTimeString()
                                    )->pluck('id')->toArray();
        Annotation::whereIn('floor_id', $deleted_floor_ids)->delete();
        print_r("deleted floors count: " . count($deleted_floor_ids) . "\n");

        $deleted_annotation_ids = Floor::withTrashed()
                                        ->where('deleted_at', '>', 
                                            Carbon::now()->subMinutes(2)->toDateTimeString()
                                        )->pluck('id')->toArray();
        Entry::whereIn('annotation_id', $deleted_annotation_ids)->delete();
        print_r("deleted annotations count: " . count($deleted_annotation_ids) . "\n");

        $deleted_entries = Entry::withTrashed()
                                ->where('deleted_at', '>', 
                                    Carbon::now()->subMinutes(2)->toDateTimeString()
                                )->get();
        print_r("deleted entries count: " . $deleted_entries->count() . "\n");
        foreach ($deleted_entries as $deleted_entry) {
            if ($deleted_entry->point)
                $deleted_entry->point->delete();
        }

        $deleted_point_ids = Point::withTrashed()
                                    ->where('deleted_at', '>', 
                                        Carbon::now()->subMinutes(2)->toDateTimeString()
                                    )->pluck('id')->toArray();
        print_r("deleted points count: " . count($deleted_point_ids) . "\n");

        $deleted_adjascent_origin_ids = Adjascent::withTrashed()
                                                ->where('deleted_at', '>', 
                                                    Carbon::now()->subMinutes(2)->toDateTimeString()
                                                )->pluck('origin_id')->toArray();

        $deleted_adjascent_destination_ids = Adjascent::withTrashed()
                                                ->where('deleted_at', '>', 
                                                    Carbon::now()->subMinutes(2)->toDateTimeString()
                                                )->pluck('destination_id')->toArray();

        Turn::whereIn('point_id', $deleted_adjascent_destination_ids)
            ->orWhereIn('point_id', $deleted_adjascent_origin_ids)
            ->orWhereIn('point_id', $deleted_point_ids)
            ->delete();

        $deleted_turn_route_ids = Turn::withTrashed()
                                ->where('deleted_at', '>', 
                                    Carbon::now()->subMinutes(2)->toDateTimeString()
                                )->pluck('route_id')->toArray();
        print_r("deleted turns count: " . count($deleted_turn_route_ids) . "\n");

        Route::whereIn('id', $deleted_turn_route_ids)->delete();

        $deleted_route_ids = Route::withTrashed()
                                    ->where('deleted_at', '>', 
                                        Carbon::now()->subMinutes(2)->toDateTimeString()
                                    )->pluck('id')->toArray();
        print_r("deleted routes count: " . count($deleted_route_ids) . "\n");

        $processed = false;
        $vias = SubCategory::where('floor_trans', 1)->pluck('name')->toArray();
        $vias[] = "none";
        $vias[] = "any";
        $via_count = 0;
        do {
            $processed = $this->createRoute($vias[$via_count]);

            if (!$processed)
                $via_count++;
        } while (!$processed && $via_count < count($vias));

        print_r("end!!!\n");
    }

    function createRoute ($via) {
        $routed_point_ids = [];

        $origin_point = null;
        $routed_annotation_ids = [];
        $unrouted_annotation_ids = [];
        $no_route_annotation_ids = [];
        $unrouted_annotations = collect();
        do {
            $origin_point = Point::whereNotIn('id', $routed_point_ids)->first();

            if (!$origin_point)
                break;

            $origin_annotation = Annotation::select('annotations.id')
                                            ->where('entries.point_id', $origin_point->id)
                                            ->join('entries', 'annotations.id', 'annotation_id')
                                            ->first();

            if ($via && $via != '')
                $routed_annotation_ids = Route::where(array('origin_point_id' => $origin_point->id, 'via' => $via))
                                                ->join('points', 'points.id', 'destination_point_id')
                                                ->join('entries', 'points.id', 'point_id')
                                                ->pluck('annotation_id')->toArray();
            else
                $routed_annotation_ids = Route::where('origin_point_id', $origin_point->id)
                                                ->join('points', 'points.id', 'destination_point_id')
                                                ->join('entries', 'points.id', 'point_id')
                                                ->pluck('annotation_id')->toArray();

            if ($via && $via != '')
                $no_route_annotation_ids = NoRoute::where(array('origin_point_id' => $origin_point->id, 'via' => $via))
                                                ->pluck('destination_annotation_id')->toArray();
            else
                $no_route_annotation_ids = Route::where('origin_point_id', $origin_point->id)
                                                ->pluck('destination_annotation_id')->toArray();
            
            if ($via && $via != '')
                $unrouted_annotations = Annotation::select(DB::raw('annotations.*'))
                                                    ->join('floors', 'floors.id', 'floor_id')
                                                    ->join('buildings', 'buildings.id', 'building_id')
                                                    ->whereNotIn('annotations.id', $routed_annotation_ids)
                                                    ->whereNotIn('annotations.id', $no_route_annotation_ids)
                                                    ->where('building_id', $origin_point->floor->building_id)
                                                    ->where('floor_id', '!=', $origin_point->floor_id);               
            else
                $unrouted_annotations = Annotation::select(DB::raw('annotations.*'))
                                                    ->join('floors', 'floors.id', 'floor_id')
                                                    ->join('buildings', 'buildings.id', 'building_id')
                                                    ->whereNotIn('annotations.id', $routed_annotation_ids)
                                                    ->whereNotIn('annotations.id', $no_route_annotation_ids)
                                                    ->where('building_id', $origin_point->floor->building_id);

            if ($origin_annotation)
                $unrouted_annotations = $unrouted_annotations->where('annotations.id', '!=', $origin_annotation->id)->get();
            else
                $unrouted_annotations = $unrouted_annotations->get();


            if ($unrouted_annotations->count() == 0)
                $routed_point_ids[] = $origin_point->id;
        } while ($unrouted_annotations->count() == 0);

        if (!$origin_point) {
            print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "no points left to process!!!");
            return false;
        }

        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "origin point id: " . $origin_point->id . "\n");
        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "routed annotations count: " . count($routed_annotation_ids) . "\n");
        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "unrouted annotations count: " . $unrouted_annotations->count() . "\n");

        $destination_annotation = $unrouted_annotations->first();
        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "destination annotation id: " . $destination_annotation->id . "\n");

        $destination_entry = null;
        $min_entries_distance = 100;

        foreach ($destination_annotation->entries as $d_entry) {
            if ($d_entry->point) {
                $distance = sqrt(pow($origin_point->longitude - $d_entry->point->longitude, 2) + pow($origin_point->latitude - $d_entry->point->latitude, 2));

                if ($distance < $min_entries_distance) {
                    $min_entries_distance = $distance;
                    $destination_entry = $d_entry;
                }
            }
        }

        if (!$destination_entry || !$destination_entry->point) {
            print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "destination annotation has no entry point");

            $no_route = NoRoute::where(array('origin_point_id' => $origin_point->id, 'destination_annotation_id' => $destination_annotation->id, 'via' => $via));

            if ($origin_point->entry && $origin_point->entry->annotation)
                $no_route = $no_route->where('origin_annotation_id', $origin_point->entry->annotation->id)->first();
            else
                $no_route = $no_route->first();

            if (!$no_route) {
                $no_route = new NoRoute;

                $no_route->origin_point_id = $origin_point->id;

                if ($origin_point->entry && $origin_point->entry->annotation)
                    $no_route->origin_annotation_id = $origin_point->entry->annotation->id;

                $no_route->destination_annotation_id = $destination_annotation->id;
                $no_route->via = $via;
                $no_route->reason = 'no entry point';
                $no_route->save();
            }

            return false;
        }

        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "destination entry id: " . $destination_entry->id . "\n");
        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "destination point id: " . $destination_entry->point->id . "\n");

        $result = BuildingsController::getRoute($destination_annotation->floor->building_id, $origin_point, $destination_entry->point, strtolower($via));

        if ($result['status'] == 'ERROR') {
            print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "error encountered while getting route!!!");

            $no_route = NoRoute::where(array('origin_point_id' => $origin_point->id, 'destination_point_id' => $destination_entry->point->id, 'via' => $via))->first();

            if (!$no_route) {
                $no_route = new NoRoute;

                $no_route->origin_point_id = $origin_point->id;
                $no_route->destination_point_id = $destination_entry->point->id;                
            }

            if ($origin_point->entry && $origin_point->entry->annotation)
                $no_route->origin_annotation_id = $origin_point->entry->annotation->id;
            
            $no_route->destination_annotation_id = $destination_annotation->id;
            $no_route->via = $via;
            $no_route->reason = 'error';
            $no_route->save();

            return false;
        }

        $route = null;

        if ($via && $via != '')
            $route = Route::where(array('origin_point_id' => $origin_point->id, 'destination_point_id' => $destination_entry->point->id, 'via' => $via))->first();
        else
            $route = Route::where(array('origin_point_id' => $origin_point->id, 'destination_point_id' => $destination_entry->point->id))->first();

        if (!$route) {
            $route = new Route;

            $route->origin_point_id = $origin_point->id;
            $route->destination_point_id = $destination_entry->point->id;
            $route->distance = $result['distance'];

            if ($via && $via != '')
                $route->via = strtolower($via);

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

        if (!$route) {
            print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "no route found!!!");

             $no_route = NoRoute::where(array('origin_point_id' => $origin_point->id, 'destination_point_id' => $destination_entry->point->id, 'via' => $via))->first();

            if (!$no_route) {
                $no_route = new NoRoute;

                $no_route->origin_point_id = $origin_point->id;
                $no_route->destination_point_id = $destination_entry->point->id;                
            }

            if ($origin_point->entry && $origin_point->entry->annotation)
                    $no_route->origin_annotation_id = $origin_point->entry->annotation->id;

            $no_route->destination_annotation_id = $destination_annotation->id;
            $no_route->via = $via;
            $no_route->reason = 'no route';
            $no_route->save();

            return false;
        }

        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "route saved!!!\n");
        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "route id: " . $route->id . "\n");
        print_r(($via && $via != '' ? '(' . $via . ') ' : '') . "steps count: " . $step . "\n");

        return true;
    }
}
