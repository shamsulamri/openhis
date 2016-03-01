<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietRating;
use Log;
use DB;
use Session;


class DietRatingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_ratings = DB::table('diet_ratings')
					->orderBy('rate_name')
					->paginate($this->paginateValue);
			return view('diet_ratings.index', [
					'diet_ratings'=>$diet_ratings
			]);
	}

	public function create()
	{
			$diet_rating = new DietRating();
			return view('diet_ratings.create', [
					'diet_rating' => $diet_rating,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet_rating = new DietRating();
			$valid = $diet_rating->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_rating = new DietRating($request->all());
					$diet_rating->rate_code = $request->rate_code;
					$diet_rating->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_ratings/id/'.$diet_rating->rate_code);
			} else {
					return redirect('/diet_ratings/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_rating = DietRating::findOrFail($id);
			return view('diet_ratings.edit', [
					'diet_rating'=>$diet_rating,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_rating = DietRating::findOrFail($id);
			$diet_rating->fill($request->input());


			$valid = $diet_rating->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_rating->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_ratings/id/'.$id);
			} else {
					return view('diet_ratings.edit', [
							'diet_rating'=>$diet_rating,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_rating = DietRating::findOrFail($id);
		return view('diet_ratings.destroy', [
			'diet_rating'=>$diet_rating
			]);

	}
	public function destroy($id)
	{	
			DietRating::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_ratings');
	}
	
	public function search(Request $request)
	{
			$diet_ratings = DB::table('diet_ratings')
					->where('rate_name','like','%'.$request->search.'%')
					->orWhere('rate_code', 'like','%'.$request->search.'%')
					->orderBy('rate_name')
					->paginate($this->paginateValue);

			return view('diet_ratings.index', [
					'diet_ratings'=>$diet_ratings,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_ratings = DB::table('diet_ratings')
					->where('rate_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_ratings.index', [
					'diet_ratings'=>$diet_ratings
			]);
	}
}
