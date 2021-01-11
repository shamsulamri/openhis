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
use App\AppointmentService;
use App\BlockType;

class BlockDateController extends Controller
{
	public $paginateValue=10;
	public $blocks = null;

	public function __construct()
	{
			$this->middleware('auth');
			$this->blocks = ['block'=>'Block','public_holiday'=>'Public Holiday', 'new_year'=>'New Year'];
	}

	public function index()
	{
			$block_dates = BlockDate::orderBy('block_date', 'desc')
					->orderBy('service_id')
					->paginate($this->paginateValue);

			return view('block_dates.index', [
					'block_dates'=>$block_dates,
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service_id'=>null,
			]);
	}

	public function create()
	{
			$block_date = new BlockDate();
			$block_date->block_recur=0;

			return view('block_dates.create', [
					'block_date' => $block_date,
					'minYear' => Carbon::now()->year,
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'blocks' => BlockType::all()->sortBy('block_name')->lists('block_name', 'block_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$block_date = new BlockDate();
			$valid = $block_date->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$block_date = new BlockDate($request->all());
					$block_date->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/block_dates/id/'.$block_date->block_id);
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
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'blocks' => BlockType::all()->sortBy('block_name')->lists('block_name', 'block_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$block_date = BlockDate::findOrFail($id);
			$block_date->fill($request->input());

			$valid = $block_date->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$block_date->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/block_dates/id/'.$id);
			} else {
					return redirect('/block_dates/'.$id.'/edit')
							->withInput()
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
			$block_dates = BlockDate::where('block_description','like','%'.$request->search.'%')
					->orderBy('block_date', 'desc');

			if ($request->service_id) {
				$block_dates = $block_dates->where('service_id', $request->service_id);
			}

			$block_dates = $block_dates->paginate($this->paginateValue);

			return view('block_dates.index', [
					'block_dates'=>$block_dates,
					'search'=>$request->search,
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service_id'=>$request->service_id?:null,
					]);
	}

	public function searchById($id)
	{
			$block_dates = BlockDate::where('block_id','=',$id)
					->paginate($this->paginateValue);

			return view('block_dates.index', [
					'block_dates'=>$block_dates,
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service_id'=>null,
			]);
	}
}
