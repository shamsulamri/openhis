<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MedicalCertificate;
use Log;
use DB;
use Session;


class MedicalCertificateController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$medical_certificates = DB::table('medical_certificates')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('medical_certificates.index', [
					'medical_certificates'=>$medical_certificates
			]);
	}

	public function create()
	{
			$medical_certificate = new MedicalCertificate();
			return view('medical_certificates.create', [
					'medical_certificate' => $medical_certificate,
				
					]);
	}

	public function store(Request $request) 
	{
			$medical_certificate = new MedicalCertificate();
			$valid = $medical_certificate->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$medical_certificate = new MedicalCertificate($request->all());
					$medical_certificate->mc_id = $request->mc_id;
					$medical_certificate->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/medical_certificates/id/'.$medical_certificate->mc_id);
			} else {
					return redirect('/medical_certificates/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$medical_certificate = MedicalCertificate::findOrFail($id);
			return view('medical_certificates.edit', [
					'medical_certificate'=>$medical_certificate,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$medical_certificate = MedicalCertificate::findOrFail($id);
			$medical_certificate->fill($request->input());


			$valid = $medical_certificate->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$medical_certificate->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/medical_certificates/id/'.$id);
			} else {
					return view('medical_certificates.edit', [
							'medical_certificate'=>$medical_certificate,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$medical_certificate = MedicalCertificate::findOrFail($id);
		return view('medical_certificates.destroy', [
			'medical_certificate'=>$medical_certificate
			]);

	}
	public function destroy($id)
	{	
			MedicalCertificate::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/medical_certificates');
	}
	
	public function search(Request $request)
	{
			$medical_certificates = DB::table('medical_certificates')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('mc_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('medical_certificates.index', [
					'medical_certificates'=>$medical_certificates,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$medical_certificates = DB::table('medical_certificates')
					->where('mc_id','=',$id)
					->paginate($this->paginateValue);

			return view('medical_certificates.index', [
					'medical_certificates'=>$medical_certificates
			]);
	}
}
