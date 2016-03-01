<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Admission;
use Log;
use DB;
use Session;
use App\Bed;
use App\Diet;
use App\DietTexture;
use App\DietClass;
use App\Referral;
use App\AdmissionType;
use App\Encounter;

class AdmissionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$admissions = DB::table('admissions')
					->orderBy('bed_code')
					->paginate($this->paginateValue);
			return view('admissions.index', [
					'admissions'=>$admissions
			]);
	}

	public function create(Request $request)
	{
			$admission = new Admission();
			$admission->encounter_id = $request->encounter_id;
			$encounter = Encounter::findOrFail($admission->encounter_id);

			return view('admissions.create', [
					'admission' => $admission,
					'bed' => Bed::where('encounter_code',$encounter->encounter_code)->orderBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::all()->sortBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$admission = new Admission();
			$valid = $admission->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$admission = new Admission($request->all());
					$admission->admission_id = $request->admission_id;
					$admission->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/admissions/id/'.$admission->admission_id);
			} else {
					return redirect('/admissions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$admission = Admission::findOrFail($id);
			$encounter = Encounter::findOrFail($admission->encounter_id);

			return view('admissions.edit', [
					'admission'=>$admission,
					'bed' => Bed::where('encounter_code',$encounter->encounter_code)->orderBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::all()->sortBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission = Admission::findOrFail($id);
			$admission->fill($request->input());

			$admission->admission_nbm = $request->admission_nbm ?: 0;

			$valid = $admission->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$admission->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/admissions/id/'.$id);
			} else {
					return view('admissions.edit', [
							'admission'=>$admission,
							'bed' => Bed::all()->sortBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
							'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
							'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
							'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
							'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$admission = Admission::findOrFail($id);
		return view('admissions.destroy', [
			'admission'=>$admission
			]);

	}
	public function destroy($id)
	{	
			Admission::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/admissions');
	}
	
	public function search(Request $request)
	{
			$admissions = DB::table('admissions')
					->where('bed_code','like','%'.$request->search.'%')
					->orWhere('admission_id', 'like','%'.$request->search.'%')
					->orderBy('bed_code')
					->paginate($this->paginateValue);

			return view('admissions.index', [
					'admissions'=>$admissions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$admissions = DB::table('admissions')
					->where('admission_id','=',$id)
					->paginate($this->paginateValue);

			return view('admissions.index', [
					'admissions'=>$admissions
			]);
	}
}
