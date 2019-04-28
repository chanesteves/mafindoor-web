<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertToSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        $category = DB::table('categories')->where(array('name' => 'Shop'))->first();

        if (!DB::table('sub_categories')->where(array('name' => 'Clothing', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Clothing', 'category_id' => $category->id))->insert(array('name' => 'Clothing', 'icon' => 'clothing', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Footwear', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Footwear', 'category_id' => $category->id))->insert(array('name' => 'Footwear', 'icon' => 'footwear', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Accessories', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Accessories', 'category_id' => $category->id))->insert(array('name' => 'Accessories', 'icon' => 'accessories', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Medicine', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Medicine', 'category_id' => $category->id))->insert(array('name' => 'Medicine', 'icon' => 'medicine', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Jewelry', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Jewelry', 'category_id' => $category->id))->insert(array('name' => 'Jewelry', 'icon' => 'jewelry', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Computers & Gadgets', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Computers & Gadgets', 'category_id' => $category->id))->insert(array('name' => 'Computers & Gadgets', 'icon' => 'computersandgadgets', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Hardware', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Hardware', 'category_id' => $category->id))->insert(array('name' => 'Hardware', 'icon' => 'hardware', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Bookstore', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Bookstore', 'category_id' => $category->id))->insert(array('name' => 'Bookstore', 'icon' => 'bookstore', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Toys', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Toys', 'category_id' => $category->id))->insert(array('name' => 'Toys', 'icon' => 'toys', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Photos & Prints', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Photos & Prints', 'category_id' => $category->id))->insert(array('name' => 'Photos & Prints', 'icon' => 'photosandprints', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Sports', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Sports', 'category_id' => $category->id))->insert(array('name' => 'Sports', 'icon' => 'sports', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Appliances', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Appliances', 'category_id' => $category->id))->insert(array('name' => 'Appliances', 'icon' => 'appliances', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        $category = DB::table('categories')->where(array('name' => 'Services'))->first();

        if (!DB::table('sub_categories')->where(array('name' => 'Health & Beauty', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Health & Beauty', 'category_id' => $category->id))->insert(array('name' => 'Health & Beauty', 'icon' => 'healthandbeauty', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Massage', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Massage', 'category_id' => $category->id))->insert(array('name' => 'Massage', 'icon' => 'massage', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Bank & Remittance', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Bank & Remittance', 'category_id' => $category->id))->insert(array('name' => 'Bank & Remittance', 'icon' => 'bankandremittance', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'TelecommUnication & Cable', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'TelecommUnication & Cable', 'category_id' => $category->id))->insert(array('name' => 'TelecommUnication & Cable', 'icon' => 'telecommUnicationandcable', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Travel & Tours', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Travel & Tours', 'category_id' => $category->id))->insert(array('name' => 'Travel & Tours', 'icon' => 'travelandtours', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        $category = DB::table('categories')->where(array('name' => 'Food'))->first();

        if (!DB::table('sub_categories')->where(array('name' => 'Fastfood', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Fastfood', 'category_id' => $category->id))->insert(array('name' => 'Fastfood', 'icon' => 'fastfood', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Restaurant', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Restaurant', 'category_id' => $category->id))->insert(array('name' => 'Restaurant', 'icon' => 'restaurant', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Cafe & Desserts', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Cafe & Desserts', 'category_id' => $category->id))->insert(array('name' => 'Cafe & Desserts', 'icon' => 'cafeanddesserts', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Bar', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Bar', 'category_id' => $category->id))->insert(array('name' => 'Bar', 'icon' => 'bar', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Snacks', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Snacks', 'category_id' => $category->id))->insert(array('name' => 'Snacks', 'icon' => 'snacks', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        $category = DB::table('categories')->where(array('name' => 'Entertainment'))->first();

        if (!DB::table('sub_categories')->where(array('name' => 'Games', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Games', 'category_id' => $category->id))->insert(array('name' => 'Games', 'icon' => 'games', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Cinema', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Cinema', 'category_id' => $category->id))->insert(array('name' => 'Cinema', 'icon' => 'games', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        $category = DB::table('categories')->where(array('name' => 'Others'))->first();

        if (!DB::table('sub_categories')->where(array('name' => 'Elevator', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Elevator'))->insert(array('name' => 'Elevator', 'icon' => 'elevator', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Escalator', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Escalator'))->insert(array('name' => 'Escalator', 'icon' => 'escalator', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Stairs', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Stairs'))->insert(array('name' => 'Stairs', 'icon' => 'stairs', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Restroom', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Restroom'))->insert(array('name' => 'Restroom', 'icon' => 'restroom', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'ATM', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'ATM'))->insert(array('name' => 'ATM', 'icon' => 'atm', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'PWD', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'PWD'))->insert(array('name' => 'PWD', 'icon' => 'pwd', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Fire Exit', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Fire Exit'))->insert(array('name' => 'Fire Exit', 'icon' => 'fireexit', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('sub_categories')->where(array('name' => 'Lounge', 'category_id' => $category->id))->first())
            DB::table('sub_categories')->where(array('name' => 'Lounge'))->insert(array('name' => 'Lounge', 'icon' => 'lounge', 'category_id' => $category->id, 'created_at' => $now, 'updated_at' => $now));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
