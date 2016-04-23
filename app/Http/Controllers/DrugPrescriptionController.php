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
use App\UnitMeasure as Unit;
use App\DrugDosage as Dosage;
use App\DrugRoute as Route;
use App\DrugFrequency as Frequency;
use App\Period;
use App\Product;

class DrugPrescriptionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_prescriptions = DB::table('drug_prescriptions')
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
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
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
			$product = Product::find($id);
			$drug_prescription = DrugPrescription::where('drug_code','=', $id)->get();
			if (count($drug_prescription)>0) {
					$drug_prescription=$drug_prescription[0];
			} else {
					$drug_prescription = new DrugPrescription();
					$drug_prescription->drug_code = $id;
					$drug_prescription->save();
			}
					
			return view('drug_prescriptions.edit', [
					'drug_prescription'=>$drug_prescription,
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'product' => $product,
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_prescription = DrugPrescription::findOrFail($id);
			$drug_prescription->fill($request->input());

			$drug_prescription->drug_prn = $request->drug_prn ?: 0;
			$drug_prescription->drug_meal = $request->drug_meal ?: 0;

			$valid = $drug_prescription->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_prescription->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/products');
			} else {
					return view('drug_prescriptions.edit', [
							'drug_prescription'=>$drug_prescription,
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
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
			$drug_prescriptions = DB::table('drug_prescriptions')
					->where('drug_code','like','%'.$request->search.'%')
					->orWhere('prescription_id', 'like','%'.$request->search.'%')
					->orderBy('drug_code')
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

			return view('drug_prescriptions.index', [
					'drug_prescriptions'=>$drug_prescriptions
			]);
	}
}
