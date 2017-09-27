<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugPrescription;
use Log;
use DB;
use Session;
use App\Drug;
use App\DrugDosage as Dosage;
use App\DrugFrequency as Frequency;
use App\DrugRoute as Route;
use App\Period;
use App\DrugInstruction as Instruction;
use App\DrugSpecialInstruction as Special;
use App\DrugCaution as Caution;
use App\DrugIndication as Indication;


class DrugPrescriptionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_prescriptions = DB::table('drug_prescriptions as a')
					->select('prescription_id', 'product_name', 'drug_code','drug_dosage', 'route_name','frequency_name', 'dosage_name')
					->leftjoin('products as b','b.product_code','=',  'a.drug_code')
					->leftjoin('drug_routes as c','c.route_code','=',  'a.route_code')
					->leftjoin('drug_frequencies as d','d.frequency_code','=',  'a.frequency_code')
					->leftjoin('drug_dosages as e', 'e.dosage_code','=', 'a.dosage_code')
					->orderBy('drug_code')
					->paginate($this->paginateValue);

			return view('drug_prescriptions.index', [
					'drug_prescriptions'=>$drug_prescriptions
			]);
	}

	public function create()
	{
			$drug_prescription = new DrugPrescription();
			return view('drug_prescriptions.create', [
					'drug_prescription' => $drug_prescription,
					'drug' => Drug::all()->sortBy('drug_generic_name')->lists('drug_generic_name', 'drug_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'instruction' => Instruction::all()->sortBy('instruction_name')->lists('instruction_name', 'instruction_code')->prepend('',''),
					'special' => Special::all()->sortBy('special_instruction_english')->lists('special_instruction_english', 'special_code')->prepend('',''),
					'caution' => Caution::all()->sortBy('caution_english')->lists('caution_english', 'caution_code')->prepend('',''),
					'indication' => Indication::all()->sortBy('indication_description')->lists('indication_description', 'indication_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$drug_prescription = new DrugPrescription();
			$valid = $drug_prescription->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_prescription = new DrugPrescription($request->all());
					$drug_prescription->prescription_id = $request->prescription_id;
					$drug_prescription->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_prescriptions/id/'.$drug_prescription->prescription_id);
			} else {
					return redirect('/drug_prescriptions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_prescription = DrugPrescription::findOrFail($id);

			return view('drug_prescriptions.edit', [
					'drug_prescription'=>$drug_prescription,
					'drug' => Drug::all()->sortBy('drug_generic_name')->lists('drug_generic_name', 'drug_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'instruction' => Instruction::all()->sortBy('instruction_english')->lists('instruction_english', 'instruction_code')->prepend('',''),
					'special' => Special::all()->sortBy('special_instruction_english')->lists('special_instruction_english', 'special_code')->prepend('',''),
					'caution' => Caution::all()->sortBy('caution_english')->lists('caution_english', 'caution_code')->prepend('',''),
					'indication' => Indication::all()->sortBy('indication_description')->lists('indication_description', 'indication_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_prescription = DrugPrescription::findOrFail($id);
			$drug_prescription->fill($request->input());


			$valid = $drug_prescription->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_prescription->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_prescriptions/id/'.$id);
			} else {
					return view('drug_prescriptions.edit', [
							'drug_prescription'=>$drug_prescription,
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'instruction' => Instruction::all()->sortBy('instruction_name')->lists('instruction_name', 'instruction_code')->prepend('',''),
					'special' => Special::all()->sortBy('special_instruction_english')->lists('special_instruction_english', 'special_code')->prepend('',''),
					'caution' => Caution::all()->sortBy('caution_english')->lists('caution_english', 'caution_code')->prepend('',''),
					'indication' => Indication::all()->sortBy('indication_description')->lists('indication_description', 'indication_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_prescription = DrugPrescription::findOrFail($id);
		return view('drug_prescriptions.destroy', [
			'drug_prescription'=>$drug_prescription
			]);

	}
	public function destroy($id)
	{	
			DrugPrescription::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_prescriptions');
	}
	
	public function search(Request $request)
	{

			$drug_prescriptions = DB::table('drug_prescriptions as a')
					->select('prescription_id', 'product_name', 'drug_code','drug_dosage', 'route_name','frequency_name', 'dosage_name')
					->leftjoin('products as b','b.product_code','=',  'a.drug_code')
					->leftjoin('drug_routes as c','c.route_code','=',  'a.route_code')
					->leftjoin('drug_frequencies as d','d.frequency_code','=',  'a.frequency_code')
					->leftjoin('drug_dosages as e', 'e.dosage_code','=', 'a.dosage_code')
					->where('drug_code','like','%'.$request->search.'%')
					->orWhere('product_name','like','%'.$request->search.'%')
					->orWhere('prescription_id', 'like','%'.$request->search.'%')
					->orderBy('product_name')
					->paginate($this->paginateValue);

			return view('drug_prescriptions.index', [
					'drug_prescriptions'=>$drug_prescriptions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_prescriptions = DB::table('drug_prescriptions')
					->where('prescription_id','=',$id)
					->paginate($this->paginateValue);

			$drug_prescriptions = DB::table('drug_prescriptions as a')
					->select('prescription_id', 'product_name', 'drug_code','drug_dosage', 'route_name','frequency_name', 'dosage_name')
					->leftjoin('products as b','b.product_code','=',  'a.drug_code')
					->leftjoin('drug_routes as c','c.route_code','=',  'a.route_code')
					->leftjoin('drug_frequencies as d','d.frequency_code','=',  'a.frequency_code')
					->leftjoin('drug_dosages as e', 'e.dosage_code','=', 'a.dosage_code')
					->where('prescription_id', '=', $id)
					->paginate($this->paginateValue);

			return view('drug_prescriptions.index', [
					'drug_prescriptions'=>$drug_prescriptions
			]);
	}
}
