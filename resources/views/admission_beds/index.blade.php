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
			<h3>Bed Selection</h3>
		</div>
@endif
<div class='row'>
	<div class='col-md-4'>
		<div class='list-group'>
		@foreach ($wards2 as $ward2)
		<a class='list-group-item @if ($ward2->ward_code==$ward_code) {!! 'active' !!} @endif' href="/admission_beds/{{ $admission->admission_id }}/{{ $ward2->ward_code }}?flag={{ $flag }}">{{ $ward2->ward_name }}</a>
		@endforeach
		</div>
	</div>
	<div class='col-md-8'>
<form action='/admission_bed/search' method='post'>
	@can('module-patient')
    <a class="btn btn-default" href="/patients/{{ $encounter->patient_id }}" role="button">Cancel</a>
	@endcan
	@can('module-ward')
	@if (!empty($book_id))
    <a class="btn btn-default" href="/bed_bookings" role="button">Cancel</a>
	@else
    <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
	@endif
	@endcan
	{{ Form::hidden('admission_id', $admission->admission_id) }}
	{{ Form::hidden('book_id', $book_id) }}
	{{ Form::hidden('flag', $flag) }}
</form>
<br>
		@if ($admission_beds->total()>0)
		<table class="table table-hover">
		 <thead>
			<tr> 
			<th>Bed</th>
			<th>Class</th>
			<th>Patient</th>
			<th></th>
			</tr>
		  </thead>
			<tbody>
		@foreach ($admission_beds as $admission_bed)
			<?php
			$status="";
			if (!empty($book)) {
					if ($book->class_code == $admission_bed->class_code) {
						$status = "success";
					}
					if (!empty($admission_bed->patient_name)) {
							$status="";
					}
					if ($book->bed_code == $admission_bed->bed_code) {
						$status = "warning";
					}
			}
			?>
			<tr class='{{ $status }}'>
					<td>
							{{$admission_bed->bed_name}}
					</td>
					<td>
							{{$admission_bed->class_name}}
					</td>
					<td>
							{{ strtoupper($admission_bed->patient_name) }}
					</td>
					<td align='right'>
							@if (empty($admission_bed->patient_name))
									@if (!empty($book_id))
										<a class='btn btn-primary btn-xs' href='{{ URL::to('admission_beds/move/'.$admission->admission_id.'/'. $admission_bed->bed_code.'?book_id='.$book_id) }}'>
									@else
										<a class='btn btn-primary btn-xs' href='{{ URL::to('admission_beds/move/'.$admission->admission_id.'/'. $admission_bed->bed_code) }}'>
									@endif
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
								
								@if (!empty($encounter->admission->bed))
										@if ($admission->encounter->patient_id<>$admission_bed->patient_id)
											@if (empty($move))
										<a class='btn btn-default btn-xs' href='{{ URL::to('bed_bookings/create/'.$admission->encounter->patient_id.'/'.$admission->admission_id.'?class_code='.$admission_bed->class_code.'&ward_code='.$admission_bed->ward_code.'&bed_code='.$admission_bed->bed_code) }}'>
											&nbsp;&nbsp;Reserve&nbsp;&nbsp;
										</a>
											@endif
										@endif
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
		</div>
</div>
@endsection
