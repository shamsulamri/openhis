@extends('layouts.app')

@section('content')
<h1>Patient List
<a href='/patients/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/patient/search' method='post'>
	<div class='input-group'>
			<input type='text' class='form-control' placeholder="Enter name, identification or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
		<div class="alert alert-info">{{ Session::get('message') }}</div>
@else
@endif
@if ($patients->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>MRN</th> 
    <th>Name</th>
    <th>Identification</th>
    <th>Encounter</th>
    <th></th>
    <th></th>
	@can('system-administrator')	
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($patients as $patient)
	<tr>
			<td width='10%'>
					{{$patient->patient_mrn}}
			</td>
			<td>
					<a href='{{ URL::to('patients/'. $patient->patient_id.'/edit') }}'>
						@if ($patient->title)
						{{ strtoupper($patient->title->title_name) }}
						@endif
						{{ strtoupper($patient->patient_name) }}
					</a>
			</td>
			<td>
					{{ $patient->patient_new_ic }}
			</td>
			<td>
					@if (!empty($patient->getCurrentEncounter()))
							{{ $patient->getCurrentEncounter() }}
					@endif
			</td>
			<td width='20'>
				<div class="tooltip-demo">
					@if ($patient->outstandingBill()<0)
						<i class="fa fa-exclamation-triangle" data-toggle="tooltip" data-placement="left" title="Outstanding bill"></i>
					@else
						<!--
						<span class='glyphicon glyphicon-ok'></span>
						-->
					@endif
				</div>
			</td>
			<td width='20'>
				<div class="tooltip-demo">
					@if (empty($patient->getCurrentEncounter()))
					<a class='btn btn-default btn-xs pull-right' data-toggle="tooltip" data-placement="top" title="Start Encounter" href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id) }}'>
						<i class="fa fa-stethoscope"></i>
					</a>
					@endif
				</div>
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patients/delete/'. $patient->patient_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patients->appends(['search'=>$search])->render() }}
	@else
	{{ $patients->render() }}
@endif
<br>
@if ($patients->total()>0)
	{{ $patients->total() }} records found.
@else
	No record found.
@endif
@endsection
