<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BlockType;
use Log;
use DB;
use Session;


class BlockTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			//$this->middleware('auth');
	}

	public function index()
	{
			$block_types = BlockType::orderBy('block_name')
					->paginate($this->paginateValue);

			return view('block_types.index', [
					'block_types'=>$block_types
			]);
	}

	public function create()
	{
			$block_type = new BlockType();
			return view('block_types.block_type', [
					'block_type' => null,
				
					]);
	}

	public function store(Request $request) 
	{
			$block_type = new BlockType();
			$valid = $block_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$block_type = new BlockType($request->all());
					$block_type->block_code = $request->block_code;
					$block_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/block_type/search/'.$block_type->block_code);
			} else {
					return redirect('/block_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$block_type = BlockType::findOrFail($id);
			return view('block_types.block_type', [
					'block_type'=>$block_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$block_type = BlockType::findOrFail($id);
			$block_type->fill($request->input());


			$valid = $block_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$block_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/block_type/search/'.$id);
			} else {
					return view('block_types.edit', [
							'block_type'=>$block_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$block_type = BlockType::findOrFail($id);
		return view('block_types.destroy', [
			'block_type'=>$block_type
			]);

	}
	public function destroy($id)
	{	
			BlockType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/block_types');
	}
	
	public function search(Request $request)
	{
			$block_types = BlockType::where('block_name','like','%'.$request->search.'%')
					->orWhere('block_code', 'like','%'.$request->search.'%')
					->orderBy('block_name')
					->paginate($this->paginateValue);

			return view('block_types.index', [
					'block_types'=>$block_types,
					'search'=>$request->search
					]);
	}
}
