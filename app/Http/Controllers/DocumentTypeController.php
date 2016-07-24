<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DocumentType;
use Log;
use DB;
use Session;


class DocumentTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$document_types = DB::table('document_types')
					->orderBy('type_code')
					->paginate($this->paginateValue);
			return view('document_types.index', [
					'document_types'=>$document_types
			]);
	}

	public function create()
	{
			$document_type = new DocumentType();
			return view('document_types.create', [
					'document_type' => $document_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$document_type = new DocumentType();
			$valid = $document_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$document_type = new DocumentType($request->all());
					$document_type->type_code = $request->type_code;
					$document_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/document_types/id/'.$document_type->type_code);
			} else {
					return redirect('/document_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$document_type = DocumentType::findOrFail($id);
			return view('document_types.edit', [
					'document_type'=>$document_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$document_type = DocumentType::findOrFail($id);
			$document_type->fill($request->input());


			$valid = $document_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$document_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/document_types/id/'.$id);
			} else {
					return view('document_types.edit', [
							'document_type'=>$document_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$document_type = DocumentType::findOrFail($id);
		return view('document_types.destroy', [
			'document_type'=>$document_type
			]);

	}
	public function destroy($id)
	{	
			DocumentType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/document_types');
	}
	
	public function search(Request $request)
	{
			$document_types = DB::table('document_types')
					->where('type_code','like','%'.$request->search.'%')
					->orWhere('type_code', 'like','%'.$request->search.'%')
					->orderBy('type_code')
					->paginate($this->paginateValue);

			return view('document_types.index', [
					'document_types'=>$document_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$document_types = DB::table('document_types')
					->where('type_code','=',$id)
					->paginate($this->paginateValue);

			return view('document_types.index', [
					'document_types'=>$document_types
			]);
	}
}
