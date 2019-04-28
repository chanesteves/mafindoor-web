<?php

namespace App\Http\Controllers;

use App\Person;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class PeopleController extends Controller
{

	public function ajaxUpdate (Request $request, $id) {
		$this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'gender' => 'required'
        ]);

        $person = Person::find($id);

        if (!$person)
            return array('status' => 'ERROR', 'error' => 'Person does not exist.');

        $person->first_name = $request->first_name;
        $person->last_name = $request->last_name;
        $person->gender = $request->gender;
        $person->email = $request->email;
        $person->save();

        return array('status' => 'OK', 'person' => $person);
	}

    public function ajaxUploadImage(Request $request, $id)
	{
		$person = Person::find($id);

		if (!$person)
			return array('status' => 'ERROR', 'error' => 'Person does not exist. Please contact administrator.');

		if ($request->hasFile('image')) {
		    $ds = DIRECTORY_SEPARATOR;
			$storeFolder = $ds . 'images' . $ds . 'persons' . $ds . $id . $ds . 'profile';

			if ($request->file('image')->isValid()) {
				if(!is_dir(public_path() . $storeFolder)) {
			      	mkdir(public_path() . $storeFolder, 0755, TRUE);
			    }

			    $file = $request->file('image');

			    $filename = uniqid() . '.jpg';

			    if ($targetFile = $file->move(public_path() . $storeFolder, $filename)) {
			    	$pseudoFile = str_replace(public_path() . $storeFolder, $storeFolder, $targetFile);

		    		$person->image = $pseudoFile;
		    		$person->save();	    		

		    		return array('status' => 'OK', 'result' => $person);
			    }
			    else {
			    	return array('status' => 'ERROR', 'error' => '1Error encountered while uploading image.');
		    	}
		    }
		    else {
		    	return array('status' => 'ERROR', 'error' => '2Error encountered while uploading image.');
	    	}
		}
		else {
			return array('status' => 'ERROR', 'error' => '3Error encountered while uploading image.');
		}
	}
}
