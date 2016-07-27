<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Document;
use Log;
use DB;
use Session;
use App\DocumentType;
use App\DocumentStatus;
use App\Patient;
use Auth;
use Uuid;

class DocumentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$patient = Patient::where('patient_mrn', $request->patient_mrn)->first();

			$documents = Document::where('patient_mrn',$request->patient_mrn)
					->orderBy('patient_mrn')
					->paginate($this->paginateValue);

			$loan_flag = False;
			
			if (empty($request->from)) {
					$loan_flag=True;
			}

			return view('documents.index', [
					'documents'=>$documents,
					'patient'=>$patient,
					'loan_flag'=>$loan_flag,
			]);
	}

	public function create(Request $request)
	{
			$patient = Patient::where('patient_mrn', $request->patient_mrn)->first();
			$document = new Document();
			$document->document_status=1;
			return view('documents.create', [
					'document' => $document,
					'document_type' => DocumentType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'document_statuses' => DocumentStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
					'patient'=>$patient,
					]);
	}

	public function store(Request $request) 
	{
			$document = new Document();
			$valid = $document->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$document = new Document($request->all());
					$document->document_uuid = Uuid::generate();
					$document->document_id = $request->document_id;
					$document->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/documents?patient_mrn='.$request->patient_mrn);
			} else {
					return redirect('/documents/create?patient_mrn='.$request->patient_mrn)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$document = Document::findOrFail($id);
			$patient = $document->patient;

			return view('documents.edit', [
					'document'=>$document,
					'document_type' => DocumentType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'document_statuses' => DocumentStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
					'patient'=>$patient,
					]);
	}

	public function update(Request $request, $id) 
	{
			$document = Document::findOrFail($id);
			$document->fill($request->input());


			$valid = $document->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$document->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/documents?patient_mrn='.$document->patient_mrn);
			} else {
					return view('documents.edit', [
							'document'=>$document,
							'document_type' => DocumentType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$document = Document::findOrFail($id);
		return view('documents.destroy', [
			'document'=>$document
			]);

	}
	public function destroy($id)
	{	
			Document::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/documents');
	}
	
	public function search(Request $request)
	{
			$documents = DB::table('documents')
					->where('patient_mrn','like','%'.$request->search.'%')
					->orWhere('document_uuid', 'like','%'.$request->search.'%')
					->orderBy('patient_mrn')
					->paginate($this->paginateValue);

			return view('documents.index', [
					'documents'=>$documents,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$documents = DB::table('documents')
					->where('document_id','=',$id)
					->paginate($this->paginateValue);

			return view('documents.index', [
					'documents'=>$documents
			]);
	}
}
