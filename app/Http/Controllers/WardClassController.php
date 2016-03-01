<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\WardClass;
use Log;
use DB;
use Session;


class WardClassController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$ward_classes = DB::table('ward_classes')
					->orderBy('class_name')
					->paginate($this->paginateValue);
			return view('ward_classes.index', [
					'ward_classes'=>$ward_classes
			]);
	}

	public function create()
	{
			$ward_class = new WardClass();
			return view('ward_classes.create', [
					'ward_class' => $ward_class,
				
					]);
	}

	public function store(Request $request) 
	{
			$ward_class = new WardClass();
			$valid = $ward_class->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$ward_class = new WardClass($request->all());
					$ward_class->class_code = $request->class_code;
					$ward_class->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/ward_classes/id/'.$ward_class->class_code);
			} else {
					return redirect('/ward_classes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$ward_class = WardClass::findOrFail($id);
			return view('ward_classes.edit', [
					'ward_class'=>$ward_class,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$ward_class = WardClass::findOrFail($id);
			$ward_class->fill($request->input());


			$valid = $ward_class->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$ward_class->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/ward_classes/id/'.$id);
			} else {
					return view('ward_classes.edit', [
							'ward_class'=>$ward_class,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$ward_class = WardClass::findOrFail($id);
		return view('ward_classes.destroy', [
			'ward_class'=>$ward_class
			]);

	}
	public function destroy($id)
	{	
			WardClass::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/ward_classes');
	}
	
	public function search(Request $request)
	{
			$ward_classes = DB::table('ward_classes')
					->where('class_name','like','%'.$request->search.'%')
					->orWhere('class_code', 'like','%'.$request->search.'%')
					->orderBy('class_name')
					->paginate($this->paginateValue);

			return view('ward_classes.index', [
					'ward_classes'=>$ward_classes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$ward_classes = DB::table('ward_classes')
					->where('class_code','=',$id)
					->paginate($this->paginateValue);

			return view('ward_classes.index', [
					'ward_classes'=>$ward_classes
			]);
	}
}
