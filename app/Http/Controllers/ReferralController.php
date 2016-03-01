<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Referral;
use Log;
use DB;
use Session;


class ReferralController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$referrals = DB::table('ref_referrals')
					->orderBy('referral_name')
					->paginate($this->paginateValue);
			return view('referrals.index', [
					'referrals'=>$referrals
			]);
	}

	public function create()
	{
			$referral = new Referral();
			return view('referrals.create', [
					'referral' => $referral,
				
					]);
	}

	public function store(Request $request) 
	{
			$referral = new Referral();
			$valid = $referral->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$referral = new Referral($request->all());
					$referral->referral_code = $request->referral_code;
					$referral->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/referrals/id/'.$referral->referral_code);
			} else {
					return redirect('/referrals/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$referral = Referral::findOrFail($id);
			return view('referrals.edit', [
					'referral'=>$referral,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$referral = Referral::findOrFail($id);
			$referral->fill($request->input());


			$valid = $referral->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$referral->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/referrals/id/'.$id);
			} else {
					return view('referrals.edit', [
							'referral'=>$referral,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$referral = Referral::findOrFail($id);
		return view('referrals.destroy', [
			'referral'=>$referral
			]);

	}
	public function destroy($id)
	{	
			Referral::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/referrals');
	}
	
	public function search(Request $request)
	{
			$referrals = DB::table('ref_referrals')
					->where('referral_name','like','%'.$request->search.'%')
					->orWhere('referral_code', 'like','%'.$request->search.'%')
					->orderBy('referral_name')
					->paginate($this->paginateValue);

			return view('referrals.index', [
					'referrals'=>$referrals,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$referrals = DB::table('ref_referrals')
					->where('referral_code','=',$id)
					->paginate($this->paginateValue);

			return view('referrals.index', [
					'referrals'=>$referrals
			]);
	}
}
