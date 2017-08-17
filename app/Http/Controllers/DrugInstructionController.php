<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugInstruction;
use Log;
use DB;
use Session;


class DrugInstructionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_instructions = DB::table('drug_instructions')
					->orderBy('instruction_english')
					->paginate($this->paginateValue);
			return view('drug_instructions.index', [
					'drug_instructions'=>$drug_instructions
			]);
	}

	public function create()
	{
			$drug_instruction = new DrugInstruction();
			return view('drug_instructions.create', [
					'drug_instruction' => $drug_instruction,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_instruction = new DrugInstruction();
			$valid = $drug_instruction->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_instruction = new DrugInstruction($request->all());
					$drug_instruction->instruction_code = $request->instruction_code;
					$drug_instruction->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_instructions/id/'.$drug_instruction->instruction_code);
			} else {
					return redirect('/drug_instructions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_instruction = DrugInstruction::findOrFail($id);
			return view('drug_instructions.edit', [
					'drug_instruction'=>$drug_instruction,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_instruction = DrugInstruction::findOrFail($id);
			$drug_instruction->fill($request->input());


			$valid = $drug_instruction->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_instruction->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_instructions/id/'.$id);
			} else {
					return view('drug_instructions.edit', [
							'drug_instruction'=>$drug_instruction,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_instruction = DrugInstruction::findOrFail($id);
		return view('drug_instructions.destroy', [
			'drug_instruction'=>$drug_instruction
			]);

	}
	public function destroy($id)
	{	
			DrugInstruction::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_instructions');
	}
	
	public function search(Request $request)
	{
			$drug_instructions = DB::table('drug_instructions')
					->where('instruction_english','like','%'.$request->search.'%')
					->orWhere('instruction_code', 'like','%'.$request->search.'%')
					->orderBy('instruction_english')
					->paginate($this->paginateValue);

			return view('drug_instructions.index', [
					'drug_instructions'=>$drug_instructions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_instructions = DB::table('drug_instructions')
					->where('instruction_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_instructions.index', [
					'drug_instructions'=>$drug_instructions
			]);
	}
}
