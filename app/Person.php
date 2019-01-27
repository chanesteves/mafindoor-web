<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'people';

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'id');
    }
}
