<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
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
	protected $table = 'buildings';

    public function floors()
    {
        return $this->hasMany('App\Floor', 'building_id', 'id');
    }

    public function annotations() {
        $floor_ids = $this->floors()->pluck('id');

        return Annotation::whereIn('floor_id', $floor_ids);
    }

    public function spaces() {
        $others_category = Category::where('name', 'Others')->first();
        $others_sub_category_ids = $others_category->sub_categories->pluck('id');

        return $this->annotations()->whereNotIn('sub_category_id', $others_sub_category_ids);
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function images()
    {
        return $this->belongsToMany('App\Image', 'building_images', 'building_id', 'image_id')->withTimestamps();
    }

    public function searches()
    {
        return $this->hasMany('App\Activity', 'object_id', 'id')->where('object_type', 'App\\Building')->where('request_type', 'search');
    }

    public function adjascents()
    {
        return $this->hasMany('App\Adjascent', 'building_id', 'id');
    }
}
