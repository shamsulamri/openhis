@extends('layouts.app')

@section('content')
<h1>Encounter List</h1>
<br>
<form action='/encounter/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@if ($encounters->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>MRN</th>
    <th>Patient</th>
    <th>Type</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($encounters as $encounter)
	<tr>
			<td>
					{{ date('d F Y, H:i', strtotime($encounter->created_at)) }}
			</td>
			<td>
					{{ $encounter->patient->patient_mrn }}
			</td>
			<td>

					<a href='{{ URL::to('encounters/'. $encounter->encounter_id . '/edit') }}'>
						{{$encounter->patient->patient_name}}
					</a>
			</td>
			<td>
					{{$encounter->encounterType->encounter_name}}
			</td>
			<td align='right'>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('encounters/delete/'. $encounter->encounter_id) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $encounters->appends(['search'=>$search])->render() }}
	@else
	{{ $encounters->render() }}
@endif
<br>
@if ($encounters->total()>0)
	{{ $encounters->total() }} records found.
@else
	No record found.
@endif
@endsection
