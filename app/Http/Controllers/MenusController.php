<?php

namespace App\Http\Controllers;

use App\Menu;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MenusController extends Controller
{
    public function ajaxStore(Request $request)
	{
		$this->validate($request, [
			'long_name' => 'required',
			'short_name' => 'required',
			'sequence' => 'required'
		]);
		
		$menu = Menu::where('long_name', $request->long_name)->first();

		if ($menu)
			return array('status' => 'ERROR', 'error' => $request->long_name . ' already exists.');
		

		$menu = new Menu;

		$menu->long_name = $request->long_name;
		$menu->short_name = $request->short_name;
		$menu->link = $request->link;
		$menu->parent_id = $request->parent;
		$menu->sequence = $request->sequence;

		$menu->save();

		return array('status' => 'OK', 'menu' => $menu);
	}

	public function ajaxShow($id) {
		$menu = Menu::find($id);

		return array('status' => 'OK', 'menu' => $menu);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'long_name' => 'required',
			'short_name' => 'required',
			'sequence' => 'required'
		]);

		$menu = Menu::find($id);

		if (!$menu)
			return array('status' => 'ERROR', 'error' => 'Menu not found.');

		$menu->long_name = $request->long_name;
		$menu->short_name = $request->short_name;
		$menu->link = $request->link;
		$menu->parent_id = $request->parent;
		$menu->sequence = $request->sequence;
		$menu->save();

		return array('status' => 'OK', 'result' => $menu);
	}

	public function ajaxDestroy($id)
	{
		$menu = Menu::find($id);

		if (!$menu)
			return array('status' => 'ERROR', 'error' => 'Menu not found.');

		$menu->delete();

		return array('status' => 'OK');
	}
}
