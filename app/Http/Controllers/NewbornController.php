<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Newborn;
use Log;
use DB;
use Session;
use App\DeliveryMode as Delivery;
use App\BirthComplication as Complication;
use App\BirthType as Birth;


class NewbornController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$newborns = DB::table('enc_newborns')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('newborns.index', [
					'newborns'=>$newborns
			]);
	}

	public function create()
	{
			$newborn = new Newborn();
			return view('newborns.create', [
					'newborn' => $newborn,
					'delivery' => Delivery::all()->sortBy('delivery_name')->lists('delivery_name', 'delivery_code')->prepend('',''),
					'complication' => Complication::all()->sortBy('complication_name')->lists('complication_name', 'complication_code')->prepend('',''),
					'birth' => Birth::all()->sortBy('birth_name')->lists('birth_name', 'birth_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$newborn = new Newborn();
			$valid = $newborn->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$newborn = new Newborn($request->all());
					$newborn->newborn_id = $request->newborn_id;
					$newborn->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/newborns/id/'.$newborn->newborn_id);
			} else {
					return redirect('/newborns/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$newborn = Newborn::findOrFail($id);
			return view('newborns.edit', [
					'newborn'=>$newborn,
					'delivery' => Delivery::all()->sortBy('delivery_name')->lists('delivery_name', 'delivery_code')->prepend('',''),
					'complication' => Complication::all()->sortBy('complication_name')->lists('complication_name', 'complication_code')->prepend('',''),
					'birth' => Birth::all()->sortBy('birth_name')->lists('birth_name', 'birth_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$newborn = Newborn::findOrFail($id);
			$newborn->fill($request->input());

			$newborn->newborn_g6pd = $request->newborn_g6pd ?: 0;
			$newborn->newborn_hepatitis_b = $request->newborn_hepatitis_b ?: 0;
			$newborn->newborn_bcg = $request->newborn_bcg ?: 0;
			$newborn->newborn_vitamin_k = $request->newborn_vitamin_k ?: 0;
			$newborn->newborn_term = $request->newborn_term ?: 0;
			$newborn->newborn_thyroid = $request->newborn_thyroid ?: 0;

			$valid = $newborn->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$newborn->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/newborns/id/'.$id);
			} else {
					return view('newborns.edit', [
							'newborn'=>$newborn,
					'delivery' => Delivery::all()->sortBy('delivery_name')->lists('delivery_name', 'delivery_code')->prepend('',''),
					'complication' => Complication::all()->sortBy('complication_name')->lists('complication_name', 'complication_code')->prepend('',''),
					'birth' => Birth::all()->sortBy('birth_name')->lists('birth_name', 'birth_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$newborn = Newborn::findOrFail($id);
		return view('newborns.destroy', [
			'newborn'=>$newborn
			]);

	}
	public function destroy($id)
	{	
			Newborn::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/newborns');
	}
	
	public function search(Request $request)
	{
			$newborns = DB::table('enc_newborns')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('newborn_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('newborns.index', [
					'newborns'=>$newborns,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$newborns = DB::table('enc_newborns')
					->where('newborn_id','=',$id)
					->paginate($this->paginateValue);

			return view('newborns.index', [
					'newborns'=>$newborns
			]);
	}
}
