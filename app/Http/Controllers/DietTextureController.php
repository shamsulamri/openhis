<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietTexture;
use Log;
use DB;
use Session;
use App\Diet;


class DietTextureController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_textures = DB::table('diet_textures')
					->orderBy('texture_name')
					->paginate($this->paginateValue);
			return view('diet_textures.index', [
					'diet_textures'=>$diet_textures
			]);
	}

	public function create()
	{
			$diet_texture = new DietTexture();
			return view('diet_textures.create', [
					'diet_texture' => $diet_texture,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$diet_texture = new DietTexture();
			$valid = $diet_texture->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_texture = new DietTexture($request->all());
					$diet_texture->texture_code = $request->texture_code;
					$diet_texture->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_textures/id/'.$diet_texture->texture_code);
			} else {
					return redirect('/diet_textures/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_texture = DietTexture::findOrFail($id);
			return view('diet_textures.edit', [
					'diet_texture'=>$diet_texture,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_texture = DietTexture::findOrFail($id);
			$diet_texture->fill($request->input());


			$valid = $diet_texture->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_texture->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_textures/id/'.$id);
			} else {
					return view('diet_textures.edit', [
							'diet_texture'=>$diet_texture,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_texture = DietTexture::findOrFail($id);
		return view('diet_textures.destroy', [
			'diet_texture'=>$diet_texture
			]);

	}
	public function destroy($id)
	{	
			DietTexture::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_textures');
	}
	
	public function search(Request $request)
	{
			$diet_textures = DB::table('diet_textures')
					->where('texture_name','like','%'.$request->search.'%')
					->orWhere('texture_code', 'like','%'.$request->search.'%')
					->orderBy('texture_name')
					->paginate($this->paginateValue);

			return view('diet_textures.index', [
					'diet_textures'=>$diet_textures,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_textures = DB::table('diet_textures')
					->where('texture_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_textures.index', [
					'diet_textures'=>$diet_textures
			]);
	}
}
