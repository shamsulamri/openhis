@extends('layouts.app')

@section('content')
<h1>Consultation Annotation Index
<a href='/consultation_annotations/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/consultation_annotation/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($consultation_annotations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>consultation_id</th>
    <th>annotation_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_annotations as $consultation_annotation)
	<tr>
			<td>
					<a href='{{ URL::to('consultation_annotations/'. $consultation_annotation->annotation_id . '/edit') }}'>
						{{$consultation_annotation->consultation_id}}
					</a>
			</td>
			<td>
					{{$consultation_annotation->annotation_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultation_annotations/delete/'. $consultation_annotation->annotation_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $consultation_annotations->appends(['search'=>$search])->render() }}
	@else
	{{ $consultation_annotations->render() }}
@endif
<br>
@if ($consultation_annotations->total()>0)
	{{ $consultation_annotations->total() }} records found.
@else
	No record found.
@endif
@endsection
