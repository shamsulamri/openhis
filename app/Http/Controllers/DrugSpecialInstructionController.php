<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugSpecialInstruction;
use Log;
use DB;
use Session;


class DrugSpecialInstructionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_special_instructions = DB::table('drug_special_instructions')
					->orderBy('special_instruction_english')
					->paginate($this->paginateValue);
			return view('drug_special_instructions.index', [
					'drug_special_instructions'=>$drug_special_instructions
			]);
	}

	public function create()
	{
			$drug_special_instruction = new DrugSpecialInstruction();
			return view('drug_special_instructions.create', [
					'drug_special_instruction' => $drug_special_instruction,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_special_instruction = new DrugSpecialInstruction();
			$valid = $drug_special_instruction->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_special_instruction = new DrugSpecialInstruction($request->all());
					$drug_special_instruction->special_code = $request->special_code;
					$drug_special_instruction->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_special_instructions/id/'.$drug_special_instruction->special_code);
			} else {
					return redirect('/drug_special_instructions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_special_instruction = DrugSpecialInstruction::findOrFail($id);
			return view('drug_special_instructions.edit', [
					'drug_special_instruction'=>$drug_special_instruction,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_special_instruction = DrugSpecialInstruction::findOrFail($id);
			$drug_special_instruction->fill($request->input());


			$valid = $drug_special_instruction->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_special_instruction->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_special_instructions/id/'.$id);
			} else {
					return view('drug_special_instructions.edit', [
							'drug_special_instruction'=>$drug_special_instruction,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_special_instruction = DrugSpecialInstruction::findOrFail($id);
		return view('drug_special_instructions.destroy', [
			'drug_special_instruction'=>$drug_special_instruction
			]);

	}
	public function destroy($id)
	{	
			DrugSpecialInstruction::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_special_instructions');
	}
	
	public function search(Request $request)
	{
			$drug_special_instructions = DB::table('drug_special_instructions')
					->where('special_instruction_english','like','%'.$request->search.'%')
					->orWhere('special_code', 'like','%'.$request->search.'%')
					->orderBy('special_instruction_english')
					->paginate($this->paginateValue);

			return view('drug_special_instructions.index', [
					'drug_special_instructions'=>$drug_special_instructions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_special_instructions = DB::table('drug_special_instructions')
					->where('special_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_special_instructions.index', [
					'drug_special_instructions'=>$drug_special_instructions
			]);
	}
}
