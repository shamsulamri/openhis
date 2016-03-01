@extends('layouts.app')

@section('content')
<h1>Medical Alert Index</h1>
<br>
<form action='/medical_alert/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/medical_alerts/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($medical_alerts->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>patient_id</th>
    <th>alert_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($medical_alerts as $medical_alert)
	<tr>
			<td>
					<a href='{{ URL::to('medical_alerts/'. $medical_alert->alert_id . '/edit') }}'>
						{{$medical_alert->patient_id}}
					</a>
			</td>
			<td>
					{{$medical_alert->alert_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('medical_alerts/delete/'. $medical_alert->alert_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $medical_alerts->appends(['search'=>$search])->render() }}
	@else
	{{ $medical_alerts->render() }}
@endif
<br>
@if ($medical_alerts->total()>0)
	{{ $medical_alerts->total() }} records found.
@else
	No record found.
@endif
@endsection
