<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BlockDate;
use Log;
use DB;
use Session;
use Carbon\Carbon;

class BlockDateController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$block_dates = DB::table('appointment_block_dates')
					->orderBy('block_name')
					->paginate($this->paginateValue);
			return view('block_dates.index', [
					'block_dates'=>$block_dates
			]);
	}

	public function create()
	{
			$block_date = new BlockDate();
			return view('block_dates.create', [
					'block_date' => $block_date,
					'minYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$block_date = new BlockDate();
			$valid = $block_date->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$block_date = new BlockDate($request->all());
					$block_date->block_code = $request->block_code;
					$block_date->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/block_dates/id/'.$block_date->block_code);
			} else {
					return redirect('/block_dates/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$block_date = BlockDate::findOrFail($id);
			return view('block_dates.edit', [
					'block_date'=>$block_date,
					'minYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$block_date = BlockDate::findOrFail($id);
			$block_date->fill($request->input());

			$block_date->block_recur_annually = $request->block_recur_annually ?: 0;
			$block_date->block_recur_weekly = $request->block_recur_weekly ?: 0;
			$block_date->block_recur_monthly = $request->block_recur_monthly ?: 0;

			$valid = $block_date->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$block_date->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/block_dates/id/'.$id);
			} else {
					return view('block_dates.edit', [
							'block_date'=>$block_date,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$block_date = BlockDate::findOrFail($id);
		return view('block_dates.destroy', [
			'block_date'=>$block_date
			]);

	}
	public function destroy($id)
	{	
			BlockDate::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/block_dates');
	}
	
	public function search(Request $request)
	{
			$block_dates = DB::table('appointment_block_dates')
					->where('block_name','like','%'.$request->search.'%')
					->orWhere('block_code', 'like','%'.$request->search.'%')
					->orderBy('block_name')
					->paginate($this->paginateValue);

			return view('block_dates.index', [
					'block_dates'=>$block_dates,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$block_dates = DB::table('appointment_block_dates')
					->where('block_code','=',$id)
					->paginate($this->paginateValue);

			return view('block_dates.index', [
					'block_dates'=>$block_dates
			]);
	}
}
