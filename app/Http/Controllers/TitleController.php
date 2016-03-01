<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Title;
use Log;
use DB;
use Session;


class TitleController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$titles = DB::table('ref_titles')
					->orderBy('title_name')
					->paginate($this->paginateValue);
			return view('titles.index', [
					'titles'=>$titles
			]);
	}

	public function create()
	{
			$title = new Title();
			return view('titles.create', [
					'title' => $title,
				
					]);
	}

	public function store(Request $request) 
	{
			$title = new Title();
			$valid = $title->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$title = new Title($request->all());
					$title->title_code = $request->title_code;
					$title->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/titles/id/'.$title->title_code);
			} else {
					return redirect('/titles/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$title = Title::findOrFail($id);
			return view('titles.edit', [
					'title'=>$title,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$title = Title::findOrFail($id);
			$title->fill($request->input());


			$valid = $title->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$title->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/titles/id/'.$id);
			} else {
					return view('titles.edit', [
							'title'=>$title,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$title = Title::findOrFail($id);
		return view('titles.destroy', [
			'title'=>$title
			]);

	}
	public function destroy($id)
	{	
			Title::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/titles');
	}
	
	public function search(Request $request)
	{
			$titles = DB::table('ref_titles')
					->where('title_name','like','%'.$request->search.'%')
					->orWhere('title_code', 'like','%'.$request->search.'%')
					->orderBy('title_name')
					->paginate($this->paginateValue);

			return view('titles.index', [
					'titles'=>$titles,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$titles = DB::table('ref_titles')
					->where('title_code','=',$id)
					->paginate($this->paginateValue);

			return view('titles.index', [
					'titles'=>$titles
			]);
	}
}
