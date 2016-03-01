<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultationProcedure;
use Log;
use DB;
use Session;


class ConsultationProcedureController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultation_procedures = DB::table('consultation_procedures')
					->orderBy('procedure_description')
					->paginate($this->paginateValue);
			return view('consultation_procedures.index', [
					'consultation_procedures'=>$consultation_procedures
			]);
	}

	public function create(Request $request)
	{
			$consultation_procedure = new ConsultationProcedure();
			if (empty($request->consultation_id)==false) {
					$consultation_procedure->consultation_id = $request->consultation_id;
			}

			return view('consultation_procedures.create', [
					'consultation_procedure' => $consultation_procedure,
					]);
	}

	public function store(Request $request) 
	{
			$consultation_procedure = new ConsultationProcedure();
			$valid = $consultation_procedure->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_procedure = new ConsultationProcedure($request->all());
					$consultation_procedure->id = $request->id;
					$consultation_procedure->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_procedures/id/'.$consultation_procedure->id);
			} else {
					return redirect('/consultation_procedures/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_procedure = ConsultationProcedure::findOrFail($id);
			return view('consultation_procedures.edit', [
					'consultation_procedure'=>$consultation_procedure,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation_procedure = ConsultationProcedure::findOrFail($id);
			$consultation_procedure->fill($request->input());

			$consultation_procedure->procedure_is_principal = $request->procedure_is_principal ?: 0;

			$valid = $consultation_procedure->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation_procedure->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_procedures/id/'.$id);
			} else {
					return view('consultation_procedures.edit', [
							'consultation_procedure'=>$consultation_procedure,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation_procedure = ConsultationProcedure::findOrFail($id);
		return view('consultation_procedures.destroy', [
			'consultation_procedure'=>$consultation_procedure
			]);

	}
	public function destroy($id)
	{	
			ConsultationProcedure::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_procedures');
	}
	
	public function search(Request $request)
	{
			$consultation_procedures = DB::table('consultation_procedures')
					->where('procedure_description','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('procedure_description')
					->paginate($this->paginateValue);

			return view('consultation_procedures.index', [
					'consultation_procedures'=>$consultation_procedures,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultation_procedures = DB::table('consultation_procedures')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('consultation_procedures.index', [
					'consultation_procedures'=>$consultation_procedures
			]);
	}
}
