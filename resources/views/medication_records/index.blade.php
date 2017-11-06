@extends('layouts.app')

@section('content')
<h1>Medication Record Index
<a href='/medication_records/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/medication_record/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($medication_records->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>order_id</th>
    <th>medication_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($medication_records as $medication_record)
	<tr>
			<td>
					<a href='{{ URL::to('medication_records/'. $medication_record->medication_id . '/edit') }}'>
						{{$medication_record->order_id}}
					</a>
			</td>
			<td>
					{{$medication_record->medication_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('medication_records/delete/'. $medication_record->medication_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $medication_records->appends(['search'=>$search])->render() }}
	@else
	{{ $medication_records->render() }}
@endif
<br>
@if ($medication_records->total()>0)
	{{ $medication_records->total() }} records found.
@else
	No record found.
@endif
@endsection
