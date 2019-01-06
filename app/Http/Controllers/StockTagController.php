<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockTag;
use Log;
use DB;
use Session;


class StockTagController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_tags = StockTag::orderBy('tag_name')
					->paginate($this->paginateValue);

			return view('stock_tags.index', [
					'stock_tags'=>$stock_tags
			]);
	}

	public function create()
	{
			$stock_tag = new StockTag();
			return view('stock_tags.create', [
					'stock_tag' => $stock_tag,
				
					]);
	}

	public function store(Request $request) 
	{
			$stock_tag = new StockTag();
			$valid = $stock_tag->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_tag = new StockTag($request->all());
					$stock_tag->tag_code = $request->tag_code;
					$stock_tag->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_tags/id/'.$stock_tag->tag_code);
			} else {
					return redirect('/stock_tags/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_tag = StockTag::findOrFail($id);
			return view('stock_tags.edit', [
					'stock_tag'=>$stock_tag,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_tag = StockTag::findOrFail($id);
			$stock_tag->fill($request->input());


			$valid = $stock_tag->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_tag->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_tags/id/'.$id);
			} else {
					return view('stock_tags.edit', [
							'stock_tag'=>$stock_tag,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_tag = StockTag::findOrFail($id);
		return view('stock_tags.destroy', [
			'stock_tag'=>$stock_tag
			]);

	}
	public function destroy($id)
	{	
			StockTag::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_tags');
	}
	
	public function search(Request $request)
	{
			$stock_tags = DB::table('stock_tags')
					->where('tag_name','like','%'.$request->search.'%')
					->orWhere('tag_code', 'like','%'.$request->search.'%')
					->orderBy('tag_name')
					->paginate($this->paginateValue);

			return view('stock_tags.index', [
					'stock_tags'=>$stock_tags,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_tags = DB::table('stock_tags')
					->where('tag_code','=',$id)
					->paginate($this->paginateValue);

			return view('stock_tags.index', [
					'stock_tags'=>$stock_tags
			]);
	}
}
