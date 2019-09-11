<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
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
	protected $table = 'categories';

	public function sub_categories()
    {
        return $this->hasMany('App\SubCategory', 'category_id', 'id');
    }

    public function searches()
    {
        return $this->hasMany('App\Activity', 'object_id', 'id')->where('object_type', 'App\\Category')->where('request_type', 'search');
    }
}
