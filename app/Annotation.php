<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annotation extends Model
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
	protected $table = 'annotations';

    public function sub_category()
    {
        return $this->belongsTo('App\SubCategory', 'sub_category_id', 'id');
    }

    public function floor()
    {
        return $this->belongsTo('App\Floor', 'floor_id', 'id');
    }
}
