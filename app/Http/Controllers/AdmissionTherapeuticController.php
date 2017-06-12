<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AdmissionTherapeutic;
use Log;
use DB;
use Session;
use App\Therapeutic;


class AdmissionTherapeuticController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$admission_therapeutics = DB::table('admission_therapeutics')
					->orderBy('admission_id')
					->paginate($this->paginateValue);
			return view('admission_therapeutics.index', [
					'admission_therapeutics'=>$admission_therapeutics
			]);
	}

	public function create()
	{
			$admission_therapeutic = new AdmissionTherapeutic();
			return view('admission_therapeutics.create', [
					'admission_therapeutic' => $admission_therapeutic,
					'therapeutic' => Therapeutic::all()->sortBy('therapeutic_name')->lists('therapeutic_name', 'therapeutic_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$admission_therapeutic = new AdmissionTherapeutic();
			$valid = $admission_therapeutic->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$admission_therapeutic = new AdmissionTherapeutic($request->all());
					$admission_therapeutic->admission_id = $request->admission_id;
					$admission_therapeutic->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/admission_therapeutics/id/'.$admission_therapeutic->admission_id);
			} else {
					return redirect('/admission_therapeutics/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$admission_therapeutic = AdmissionTherapeutic::findOrFail($id);
			return view('admission_therapeutics.edit', [
					'admission_therapeutic'=>$admission_therapeutic,
					'therapeutic' => Therapeutic::all()->sortBy('therapeutic_name')->lists('therapeutic_name', 'therapeutic_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission_therapeutic = AdmissionTherapeutic::findOrFail($id);
			$admission_therapeutic->fill($request->input());

			$admission_therapeutic->therapeutic_value = $request->therapeutic_value ?: 0;

			$valid = $admission_therapeutic->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$admission_therapeutic->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/admission_therapeutics/id/'.$id);
			} else {
					return view('admission_therapeutics.edit', [
							'admission_therapeutic'=>$admission_therapeutic,
					'therapeutic' => Therapeutic::all()->sortBy('therapeutic_name')->lists('therapeutic_name', 'therapeutic_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$admission_therapeutic = AdmissionTherapeutic::findOrFail($id);
		return view('admission_therapeutics.destroy', [
			'admission_therapeutic'=>$admission_therapeutic
			]);

	}
	public function destroy($id)
	{	
			AdmissionTherapeutic::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/admission_therapeutics');
	}
	
	public function search(Request $request)
	{
			$admission_therapeutics = DB::table('admission_therapeutics')
					->where('admission_id','like','%'.$request->search.'%')
					->orWhere('admission_id', 'like','%'.$request->search.'%')
					->orderBy('admission_id')
					->paginate($this->paginateValue);

			return view('admission_therapeutics.index', [
					'admission_therapeutics'=>$admission_therapeutics,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$admission_therapeutics = DB::table('admission_therapeutics')
					->where('admission_id','=',$id)
					->paginate($this->paginateValue);

			return view('admission_therapeutics.index', [
					'admission_therapeutics'=>$admission_therapeutics
			]);
	}
}
