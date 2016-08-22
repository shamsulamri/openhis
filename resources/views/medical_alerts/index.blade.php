@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Medical Alerts</h1>
<br>
@include('common.notification')
<a href='/medical_alerts/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($medical_alerts->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Description</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($medical_alerts as $medical_alert)
	<tr>
			<td>
						{{ date('d F, H:i', strtotime($medical_alert->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('medical_alerts/'. $medical_alert->alert_id . '/edit') }}'>
						{{$medical_alert->alert_description}}
					</a>
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
