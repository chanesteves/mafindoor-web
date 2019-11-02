<?php

use Illuminate\Database\Seeder;

use App\SubCategory;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $sub_cat_names = ['Elevator', 'Escalator', 'Stairs'];

        SubCategory::whereIn('name', $sub_cat_names)->update(array('floor_trans' => 1));
    }
}
