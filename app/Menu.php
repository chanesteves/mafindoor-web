<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
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
	protected $table = 'menus';

	public function parent()
    {
        return $this->belongsTo('App\Menu', 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Menu', 'parent_id', 'id')->orderBy('sequence');
    }
}
