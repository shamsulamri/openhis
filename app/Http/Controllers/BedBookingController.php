<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BedBooking;
use Log;
use DB;
use Session;
use App\Bed;


class BedBookingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bed_bookings = DB::table('bed_bookings')
					->orderBy('patient_id')
					->paginate($this->paginateValue);
			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings
			]);
	}

	public function create()
	{
			$bed_booking = new BedBooking();
			return view('bed_bookings.create', [
					'bed_booking' => $bed_booking,
					'bed' => Bed::all()->sortBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$bed_booking = new BedBooking();
			$valid = $bed_booking->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_booking = new BedBooking($request->all());
					$bed_booking->book_id = $request->book_id;
					$bed_booking->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bed_bookings/id/'.$bed_booking->book_id);
			} else {
					return redirect('/bed_bookings/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed_booking = BedBooking::findOrFail($id);
			return view('bed_bookings.edit', [
					'bed_booking'=>$bed_booking,
					'bed' => Bed::all()->sortBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_booking = BedBooking::findOrFail($id);
			$bed_booking->fill($request->input());


			$valid = $bed_booking->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed_booking->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bed_bookings/id/'.$id);
			} else {
					return view('bed_bookings.edit', [
							'bed_booking'=>$bed_booking,
					'bed' => Bed::all()->sortBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed_booking = BedBooking::findOrFail($id);
		return view('bed_bookings.destroy', [
			'bed_booking'=>$bed_booking
			]);

	}
	public function destroy($id)
	{	
			BedBooking::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bed_bookings');
	}
	
	public function search(Request $request)
	{
			$bed_bookings = DB::table('bed_bookings')
					->where('patient_id','like','%'.$request->search.'%')
					->orWhere('book_id', 'like','%'.$request->search.'%')
					->orderBy('patient_id')
					->paginate($this->paginateValue);

			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bed_bookings = DB::table('bed_bookings')
					->where('book_id','=',$id)
					->paginate($this->paginateValue);

			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings
			]);
	}
}
