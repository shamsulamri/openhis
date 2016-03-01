<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Room;
use Log;
use DB;
use Session;


class RoomController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$rooms = DB::table('ward_rooms')
					->orderBy('room_name')
					->paginate($this->paginateValue);
			return view('rooms.index', [
					'rooms'=>$rooms
			]);
	}

	public function create()
	{
			$room = new Room();
			return view('rooms.create', [
					'room' => $room,
				
					]);
	}

	public function store(Request $request) 
	{
			$room = new Room();
			$valid = $room->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$room = new Room($request->all());
					$room->room_code = $request->room_code;
					$room->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/rooms/id/'.$room->room_code);
			} else {
					return redirect('/rooms/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$room = Room::findOrFail($id);
			return view('rooms.edit', [
					'room'=>$room,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$room = Room::findOrFail($id);
			$room->fill($request->input());


			$valid = $room->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$room->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/rooms/id/'.$id);
			} else {
					return view('rooms.edit', [
							'room'=>$room,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$room = Room::findOrFail($id);
		return view('rooms.destroy', [
			'room'=>$room
			]);

	}
	public function destroy($id)
	{	
			Room::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/rooms');
	}
	
	public function search(Request $request)
	{
			$rooms = DB::table('ward_rooms')
					->where('room_name','like','%'.$request->search.'%')
					->orWhere('room_code', 'like','%'.$request->search.'%')
					->orderBy('room_name')
					->paginate($this->paginateValue);

			return view('rooms.index', [
					'rooms'=>$rooms,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$rooms = DB::table('ward_rooms')
					->where('room_code','=',$id)
					->paginate($this->paginateValue);

			return view('rooms.index', [
					'rooms'=>$rooms
			]);
	}
}
