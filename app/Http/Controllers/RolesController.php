<?php

namespace App\Http\Controllers;

use App\Role;
use App\Menu;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    /*****************/
	/**** AJAX *******/
	/*****************/

	public function ajaxStore(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'code' => 'required'
		]);

		$role = Role::where(array('name' => $request->name))->first();

		if ($role)
			return array('status' => 'ERROR', 'error' => '\'' . $request->name . '\' already exists.');

		$role = new Role;

		$role->name = $request->name;
		$role->code = $request->code;
		$role->save();

		$menus = array_diff($request->menus, [0]);

		foreach ($menus as $menu) {
			$item = Menu::find($menu);

			if ($item->parent && $item->parent['id'])
				array_push($menus, $item->parent()->get()->first()->id);
		}

		$menus = array_unique($menus);

		$role->menus()->sync($menus);

		$role->save();

		return array('status' => 'OK', 'result' => $role);
	}

	public function ajaxShow($id) {
		$role = Role::with('menus')->find($id);

		return array('status' => 'OK', 'role' => $role);
	}

	public function ajaxUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required',
			'code' => 'required'
		]);

		$role = Role::find($id);

		if (!$role)
			return array('status' => 'ERROR', 'error' => 'Role not found.');

		$role->name = $request->name;
		$role->code = $request->code;
		$role->save();

		$menus = array_diff($request->menus, [0]);

		foreach ($menus as $menu) {
			$item = Menu::find($menu);

			if ($item->parent && $item->parent['id'])
				array_push($menus, $item->parent->id);
		}

		$menus = array_unique($menus);

		$role->menus()->sync($menus);

		$role->save();

		return array('status' => 'OK', 'result' => $role);
	}

	public function ajaxDestroy($id)
	{
		$role = Role::find($id);

		if (!$role)
			return array('status' => 'ERROR', 'error' => 'Role not found.');

		$role->delete();

		return array('status' => 'OK');
	}
}
