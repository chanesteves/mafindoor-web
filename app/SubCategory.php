<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sub_categories';

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function user_searches()
    {
        return $this->hasMany('App\UserSubCategorySearch', 'sub_category_id', 'id');
    }
}
