<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adjascent extends Model
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
	protected $table = 'adjascents';

	public function origin()
    {
        return $this->belongsTo('App\Point', 'origin_id', 'id');
    }

    public function destination()
    {
        return $this->belongsTo('App\Point', 'destination_id', 'id');
    }
}
