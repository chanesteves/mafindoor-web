<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floor extends Model
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
	protected $table = 'floors';

    public function building()
    {
        return $this->belongsTo('App\Building', 'building_id', 'id');
    }

    public function annotations()
    {
        return $this->hasMany('App\Annotation', 'floor_id', 'id');
    }

    public function points()
    {
        return $this->hasMany('App\Point', 'floor_id', 'id');
    }
}
