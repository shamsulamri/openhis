<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tourist;
use Log;
use DB;
use Session;


class TouristController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$tourists = DB::table('ref_tourists')
					->orderBy('tourist_name')
					->paginate($this->paginateValue);
			return view('tourists.index', [
					'tourists'=>$tourists
			]);
	}

	public function create()
	{
			$tourist = new Tourist();
			return view('tourists.create', [
					'tourist' => $tourist,
				
					]);
	}

	public function store(Request $request) 
	{
			$tourist = new Tourist();
			$valid = $tourist->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$tourist = new Tourist($request->all());
					$tourist->tourist_code = $request->tourist_code;
					$tourist->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/tourists/id/'.$tourist->tourist_code);
			} else {
					return redirect('/tourists/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$tourist = Tourist::findOrFail($id);
			return view('tourists.edit', [
					'tourist'=>$tourist,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$tourist = Tourist::findOrFail($id);
			$tourist->fill($request->input());


			$valid = $tourist->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$tourist->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/tourists/id/'.$id);
			} else {
					return view('tourists.edit', [
							'tourist'=>$tourist,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$tourist = Tourist::findOrFail($id);
		return view('tourists.destroy', [
			'tourist'=>$tourist
			]);

	}
	public function destroy($id)
	{	
			Tourist::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/tourists');
	}
	
	public function search(Request $request)
	{
			$tourists = DB::table('ref_tourists')
					->where('tourist_name','like','%'.$request->search.'%')
					->orWhere('tourist_code', 'like','%'.$request->search.'%')
					->orderBy('tourist_name')
					->paginate($this->paginateValue);

			return view('tourists.index', [
					'tourists'=>$tourists,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$tourists = DB::table('ref_tourists')
					->where('tourist_code','=',$id)
					->paginate($this->paginateValue);

			return view('tourists.index', [
					'tourists'=>$tourists
			]);
	}
}
