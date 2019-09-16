<?php

namespace App;

use DB;

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

    public function nears() {
        return Annotation::select(DB::raw("name, SQRT(POW(longitude - " . $this->longitude . ", 2) + POW(latitude - " . $this->latitude . ", 2)) as distance"))->where('floor_id', $this->floor_id)->where('id', '!=', $this->id)->orderBy('distance')->limit(5);
    }

    public function nears_str() {
        $nears = $this->nears()->get();

        $count = 0;
        $str = '';
        foreach ($nears as $near) {
            if ($count > 0)
                $str .= ', ';

            $str .= $near->name;
            $count++;
        }

        return $str;
    }

    public function searches()
    {
        return $this->hasMany('App\Activity', 'object_id', 'id')->where('object_type', 'App\\Annotation')->where('request_type', 'search');
    }

    public function entries()
    {
        return $this->hasMany('App\Entry', 'annotation_id', 'id');
    }
}
