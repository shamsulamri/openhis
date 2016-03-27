@extends('layouts.app')

@section('content')
<h1>Patient List</h1>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<div class='panel panel-default'>
<div class='panel-heading'>
<h4 class='panel-title'><strong>Outpatient</strong></h4>
</div>
<div class='panel-body'>
@if ($outpatient_lists->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>MRN</th>
    <th>Patient</th>
	<th>Room</th>
	</tr>
  </thead>
	<tbody>
		@foreach ($outpatient_lists as $list)
			@if ($user_id==$list->user_id || empty($list->user_id))
			<?php $status='' ?>
			@if (!empty($list->discharge_id))
					<?php $status='success' ?>
			@else
				@if ($list->consultation_status==1)
					<?php $status='warning' ?>
				@endif
			@endif
			<tr class='{{ $status }}'>
					<td width='15%'>
							{{ date('d F, H:i', strtotime($list->created_at)) }}
					</td>
					<td width='10%'>
							{{ $list->patient_mrn }}
					</td>
					<td>
						{{$list->patient_name}}
					</td>
					<td>
						{{$list->location_name}}
					</td>
					<td align='right'>
							@if (empty($list->discharge_id))
								@if ($list->consultation_status==1)
									@if ($user_id == $list->user_id)
											<a class='btn btn-warning btn-xs' href='{{ URL::to('consultations/'.$list->consultation_id.'/edit') }}'>Resume Consultation</a>
									@endif
								@else
									<a class='btn btn-primary btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $list->encounter_id) }}'>Start Consultation</a>
								@endif
							@else
								@if ($user_id == $list->user_id)
										<a class='btn btn-default btn-xs' href='{{ URL::to('consultations/'.$list->consultation_id.'/edit') }}'>Edit Consultation</a>
								@endif
							@endif
					</td>
			</tr>
			@endif
		@endforeach
		@else
				<h4>
				<span class="label label-success">
				No case
				</span>
				</h4>
@endif
</tbody>
</table>
</div>
</div>
<br>
@include('patient_lists.inpatient')

@endsection
