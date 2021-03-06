<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultationAnnotation;
use Log;
use DB;
use Session;


class ConsultationAnnotationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultation_annotations = DB::table('consultation_annotations')
					->orderBy('consultation_id')
					->paginate($this->paginateValue);
			return view('consultation_annotations.index', [
					'consultation_annotations'=>$consultation_annotations
			]);
	}

	public function create()
	{
			$consultation_annotation = new ConsultationAnnotation();
			return view('consultation_annotations.create', [
					'consultation_annotation' => $consultation_annotation,
				
					]);
	}

	public function store(Request $request) 
	{
			if ($request->ajax()) {
					$annotation = ConsultationAnnotation::where('consultation_id','=', $request->consultation_id)
									->where('annotation_image', '=', $request->annotation_image)
									->first();

					if (empty($annotation)) {
						$annotation = new ConsultationAnnotation();
					}

					$index = 99;
					if ($annotation->annotation_image == 'hopi.png') $index = 1;
					if ($annotation->annotation_image == 'hopi2.png') $index = 2;
					if ($annotation->annotation_image == 'hopi3.png') $index = 3;
					if ($annotation->annotation_image == 'hopi4.png') $index = 4;
					if ($annotation->annotation_image == 'hopi5.png') $index = 5;

					$annotation->consultation_id = $request->consultation_id;
					$annotation->annotation_image = $request->annotation_image;
					$annotation->annotation_index = $index; //$annotation->annotation_image=='hopi.png'?1:99;
					$annotation->annotation_dataurl = $request->annotation_dataurl;
					$annotation->save();
					return "Annotation store!!!!";
			}
			return;
			$consultation_annotation = new ConsultationAnnotation();
			$valid = $consultation_annotation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_annotation = new ConsultationAnnotation($request->all());
					$consultation_annotation->annotation_id = $request->annotation_id;
					$consultation_annotation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_annotations/id/'.$consultation_annotation->annotation_id);
			} else {
					return redirect('/consultation_annotations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function getAnnotation($consultation_id, $annotation_image)
	{
			$annotation = ConsultationAnnotation::where('consultation_id','=', $consultation_id)
							->where('annotation_image', '=', $annotation_image)
							->first();

			if ($annotation) {
					return $annotation->annotation_dataurl;
			}

	}

	public function show(Request $request, $annotation_image) 
	{
			if ($request->ajax()) {
					//$consulation_id = Session::get('consultation_id');
					$consultation_id = 99;
					$annotation = ConsultationAnnotation::where('consultation_id','=', $consultation_id)
									->where('annotation_image', '=', $annotation_image)
									->first();

					return $annotation->annotation_dataurl;
			}
	}

	public function edit(Request $request, $id) 
	{
			$consultation_annotation = ConsultationAnnotation::findOrFail($id);
			return view('consultation_annotations.edit', [
					'consultation_annotation'=>$consultation_annotation,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			if ($request->ajax()) {
					return "Annotation update!!!!";
			}
			return;
			$consultation_annotation = ConsultationAnnotation::findOrFail($id);
			$consultation_annotation->fill($request->input());


			$valid = $consultation_annotation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation_annotation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_annotations/id/'.$id);
			} else {
					return view('consultation_annotations.edit', [
							'consultation_annotation'=>$consultation_annotation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation_annotation = ConsultationAnnotation::findOrFail($id);
		return view('consultation_annotations.destroy', [
			'consultation_annotation'=>$consultation_annotation
			]);

	}
	public function destroy($id)
	{	
			ConsultationAnnotation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_annotations');
	}

	public function clear($consultation_id, $annotation_image)
	{	
			Log::info("Image clear.....");
			$annotation = ConsultationAnnotation::where('consultation_id','=', $consultation_id)
							->where('annotation_image', '=', $annotation_image)
							->first()
							->delete();
	}
	
	public function search(Request $request)
	{
			$consultation_annotations = DB::table('consultation_annotations')
					->where('consultation_id','like','%'.$request->search.'%')
					->orWhere('annotation_id', 'like','%'.$request->search.'%')
					->orderBy('consultation_id')
					->paginate($this->paginateValue);

			return view('consultation_annotations.index', [
					'consultation_annotations'=>$consultation_annotations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultation_annotations = DB::table('consultation_annotations')
					->where('annotation_id','=',$id)
					->paginate($this->paginateValue);

			return view('consultation_annotations.index', [
					'consultation_annotations'=>$consultation_annotations
			]);
	}

	public function saveAnnotation(Request $request)
	{
			if ($request->ajax()) {
					return "Annotation !!!!";
			}

	}
}
