@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Newborn Registration</h1>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/newborns/create?id={{ $consultation->consultation_id }}' class='btn btn-primary'>Create</a>
<br>
@if ($newborns->total()>0)
<br>
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Birthdate</th>
    <th>Name</th>
    <th>Delivery Mode</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($newborns as $newborn)
	<tr>
			<td>
					<a href='{{ URL::to('newborns/'. $newborn->newborn_id . '/edit?consultation_id='.$consultation->consultation_id) }}'>
						{{ date('d F, H:i', strtotime($newborn->created_at)) }}
					</a>
			</td>
			<td>
					{{$newborn->patient->patient_name }}
					<br>
					<small>{{$newborn->patient->patient_mrn }}</small>
			</td>
			<td>
					{{$newborn->deliveryMode->delivery_name }}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('newborns/delete/'. $newborn->newborn_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $newborns->appends(['search'=>$search])->render() }}
	@else
	{{ $newborns->render() }}
@endif
<br>
@if ($newborns->total()>0)
	{{ $newborns->total() }} records found.
@else
	No record found.
@endif
@endsection
