<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Relationship;
use Log;
use DB;
use Session;


class RelationshipController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$relationships = DB::table('ref_relationships')
					->orderBy('relation_name')
					->paginate($this->paginateValue);
			return view('relationships.index', [
					'relationships'=>$relationships
			]);
	}

	public function create()
	{
			$relationship = new Relationship();
			return view('relationships.create', [
					'relationship' => $relationship,
				
					]);
	}

	public function store(Request $request) 
	{
			$relationship = new Relationship();
			$valid = $relationship->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$relationship = new Relationship($request->all());
					$relationship->relation_code = $request->relation_code;
					$relationship->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/relationships/id/'.$relationship->relation_code);
			} else {
					return redirect('/relationships/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$relationship = Relationship::findOrFail($id);
			return view('relationships.edit', [
					'relationship'=>$relationship,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$relationship = Relationship::findOrFail($id);
			$relationship->fill($request->input());


			$valid = $relationship->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$relationship->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/relationships/id/'.$id);
			} else {
					return view('relationships.edit', [
							'relationship'=>$relationship,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$relationship = Relationship::findOrFail($id);
		return view('relationships.destroy', [
			'relationship'=>$relationship
			]);

	}
	public function destroy($id)
	{	
			Relationship::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/relationships');
	}
	
	public function search(Request $request)
	{
			$relationships = DB::table('ref_relationships')
					->where('relation_name','like','%'.$request->search.'%')
					->orWhere('relation_code', 'like','%'.$request->search.'%')
					->orderBy('relation_name')
					->paginate($this->paginateValue);

			return view('relationships.index', [
					'relationships'=>$relationships,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$relationships = DB::table('ref_relationships')
					->where('relation_code','=',$id)
					->paginate($this->paginateValue);

			return view('relationships.index', [
					'relationships'=>$relationships
			]);
	}
}
