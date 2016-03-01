<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietClass;
use Log;
use DB;
use Session;
use App\Diet;


class DietClassController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_classes = DB::table('diet_classes')
					->orderBy('class_name')
					->paginate($this->paginateValue);
			return view('diet_classes.index', [
					'diet_classes'=>$diet_classes
			]);
	}

	public function create()
	{
			$diet_class = new DietClass();
			return view('diet_classes.create', [
					'diet_class' => $diet_class,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$diet_class = new DietClass();
			$valid = $diet_class->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_class = new DietClass($request->all());
					$diet_class->class_code = $request->class_code;
					$diet_class->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_classes/id/'.$diet_class->class_code);
			} else {
					return redirect('/diet_classes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_class = DietClass::findOrFail($id);
			return view('diet_classes.edit', [
					'diet_class'=>$diet_class,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_class = DietClass::findOrFail($id);
			$diet_class->fill($request->input());


			$valid = $diet_class->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_class->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_classes/id/'.$id);
			} else {
					return view('diet_classes.edit', [
							'diet_class'=>$diet_class,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_class = DietClass::findOrFail($id);
		return view('diet_classes.destroy', [
			'diet_class'=>$diet_class
			]);

	}
	public function destroy($id)
	{	
			DietClass::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_classes');
	}
	
	public function search(Request $request)
	{
			$diet_classes = DB::table('diet_classes')
					->where('class_name','like','%'.$request->search.'%')
					->orWhere('class_code', 'like','%'.$request->search.'%')
					->orderBy('class_name')
					->paginate($this->paginateValue);

			return view('diet_classes.index', [
					'diet_classes'=>$diet_classes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_classes = DB::table('diet_classes')
					->where('class_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_classes.index', [
					'diet_classes'=>$diet_classes
			]);
	}
}
