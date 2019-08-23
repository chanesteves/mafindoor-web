<?php

namespace App\Http\Controllers;

use App\Image;

use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function ajaxDestroy ($id) {
    	$image = Image::find($id);

    	if ($image)
    		$image->delete();

    	return array('status' => 'OK', 'image' => $image);
    }
}
