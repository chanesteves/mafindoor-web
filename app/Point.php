<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point extends Model
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
	protected $table = 'points';

    public function floor()
    {
        return $this->belongsTo('App\Floor', 'floor_id', 'id');
    }

    public function adjascents()
    {
        return $this->belongsToMany('App\Point', 'adjascents', 'origin_id', 'destination_id')->withTimestamps();
    }

    public function routes()
    {
        return $this->hasMany('App\Route', 'origin_point_id', 'id');
    }
}
