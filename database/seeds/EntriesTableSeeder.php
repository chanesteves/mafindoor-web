<?php

use Illuminate\Database\Seeder;

use App\SubCategory;
use App\Annotation;
use App\Point;
use App\Entry;

class EntriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $sub_cat_names = ['ATM', 'Cashier', 'Elevator', 'Escalator', 'Fire Exit', 'Package Counter', 'PWD', 'Stairs'];
        $sub_categor_ids = SubCategory::whereIn('name', $sub_cat_names)->pluck('id')->toArray();

        $annotations = Annotation::whereIn('sub_category_id', $sub_categor_ids)->get();

        foreach ($annotations as $annotation) {
            $entry = Entry::where('annotation_id', $annotation->id)->first();
            $point = null;

            if ($entry)
                $point = $entry->point;

            if (!$point)
                $point = new Point;

            $point->longitude = $annotation->longitude;
            $point->latitude = $annotation->latitude;
            $point->floor_id = $annotation->floor_id;
            $point->type = 'door';
            $point->save();

            if (!$entry)
                $entry = new Entry;

            $entry->point_id = $point->id;
            $entry->annotation_id = $annotation->id;
            $entry->save();
        }
    }
}
