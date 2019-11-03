<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
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
	protected $table = 'entries';

    public function point()
    {
        return $this->belongsTo('App\Point', 'point_id', 'id');
    }

    public function annotation()
    {
        return $this->belongsTo('App\Annotation', 'annotation_id', 'id');
    }
}
