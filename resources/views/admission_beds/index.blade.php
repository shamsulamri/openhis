@extends('layouts.app')

@section('content')
@if ($admission != NULL)
	@include('patients.id')
@endif 	

@if ($flag==1)
<h1>Bed Movement</h1>
@else
<h1>New Encounter</h1>
<div class='page-header'>
		<h2>{{ $encounter->encounterType->encounter_name }}</h2>
</div>
<h4>Select bed for this patient.</h4>
@endif
<br>
@if ($encounter->encounter_code<>'emergency')
<form action='/admission_bed/search' method='post'>
	{{ Form::select('wards', $ward, $ward_code, ['class'=>'form-control']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<br>
    <a class="btn btn-default" href="/patients/{{ $encounter->patient_id }}" role="button">Cancel</a>
	{{ Form::submit('Refresh', ['class'=>'btn btn-primary']) }}
	{{ Form::hidden('admission_id', $admission->admission_id) }}
</form>
<br>
@endif
@if ($admission_beds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Bed</th>
    <th>Class</th>
	<th>Occupant</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($admission_beds as $admission_bed)
	<tr>
			<td>
					{{$admission_bed->bed_name}}
			</td>
			<td>
					{{$admission_bed->class_name}}
			</td>
			<td>
					{{$admission_bed->patient_name}}
			</td>
			<td align='right'>
					@if (empty($admission_bed->patient_name))
<a class='btn btn-primary btn-xs' href='{{ URL::to('admission_beds/move/'.$admission->admission_id.'/'. $admission_bed->bed_code) }}'>
							@if (empty($admission->bed->bed_code))
									Admit
							@else
								@if ($admission->bed->ward_code != $admission_bed->ward_code)
									Transfer
								@else
									@if ($admission->bed->class_code == $admission_bed->class_code)
										&nbsp;&nbsp;Swap&nbsp;&nbsp;
									@else
										Change
									@endif
								@endif
							@endif
							</a>
					@else
						@if ($admission->encounter->patient_id<>$admission_bed->patient_id)
						<a class='btn btn-default btn-xs' href='{{ URL::to('bed_bookings/create/'.$admission->encounter->patient_id.'/'.$admission_bed->admission_id) }}'>
						Booking
						</a>
						@endif
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admission_beds->appends(['search'=>$search])->render() }}
	@else
	{{ $admission_beds->render() }}
@endif
<br>
@if ($admission_beds->total()>0)
	{{ $admission_beds->total() }} records found.
@else
	No record found.
@endif
@endsection
