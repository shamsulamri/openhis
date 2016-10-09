@extends('layouts.app')

@section('content')
<h1>Patient Dependant List</h1>
<br>
<form action='/patient_dependant/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/patient_dependants/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($patient_dependants->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>patient_id</th>
    <th>id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($patient_dependants as $patient_dependant)
	<tr>
			<td>
					<a href='{{ URL::to('patient_dependants/'. $patient_dependant->id . '/edit') }}'>
						{{$patient_dependant->patient_id}}
					</a>
			</td>
			<td>
					{{$patient_dependant->id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patient_dependants/delete/'. $patient_dependant->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patient_dependants->appends(['search'=>$search])->render() }}
	@else
	{{ $patient_dependants->render() }}
@endif
<br>
@if ($patient_dependants->total()>0)
	{{ $patient_dependants->total() }} records found.
@else
	No record found.
@endif
@endsection
