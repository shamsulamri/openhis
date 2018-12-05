<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseDocument;
use Log;
use DB;
use Session;


class PurchaseDocumentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_documents = PurchaseDocument::orderBy('document_code')
					->paginate($this->paginateValue);

			return view('purchase_documents.index', [
					'purchase_documents'=>$purchase_documents
			]);
	}

	public function create()
	{
			$purchase_document = new PurchaseDocument();
			return view('purchase_documents.create', [
					'purchase_document' => $purchase_document,
				
					]);
	}

	public function store(Request $request) 
	{
			$purchase_document = new PurchaseDocument();
			$valid = $purchase_document->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_document = new PurchaseDocument($request->all());
					$purchase_document->document_code = $request->document_code;
					$purchase_document->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_documents/id/'.$purchase_document->document_code);
			} else {
					return redirect('/purchase_documents/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_document = PurchaseDocument::findOrFail($id);
			return view('purchase_documents.edit', [
					'purchase_document'=>$purchase_document,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_document = PurchaseDocument::findOrFail($id);
			$purchase_document->fill($request->input());


			$valid = $purchase_document->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_document->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_documents/id/'.$id);
			} else {
					return view('purchase_documents.edit', [
							'purchase_document'=>$purchase_document,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$purchase_document = PurchaseDocument::findOrFail($id);
		return view('purchase_documents.destroy', [
			'purchase_document'=>$purchase_document
			]);

	}
	public function destroy($id)
	{	
			PurchaseDocument::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_documents');
	}
	
	public function search(Request $request)
	{
			$purchase_documents = DB::table('purchase_documents')
					->where('document_code','like','%'.$request->search.'%')
					->orWhere('document_code', 'like','%'.$request->search.'%')
					->orderBy('document_code')
					->paginate($this->paginateValue);

			return view('purchase_documents.index', [
					'purchase_documents'=>$purchase_documents,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchase_documents = DB::table('purchase_documents')
					->where('document_code','=',$id)
					->paginate($this->paginateValue);

			return view('purchase_documents.index', [
					'purchase_documents'=>$purchase_documents
			]);
	}
}
