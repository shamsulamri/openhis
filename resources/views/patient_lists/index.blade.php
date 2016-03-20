@extends('layouts.app')

@section('content')
<h1>Patient List Index</h1>
<h4>{{ $location->location_name }}</h4>
<br>
<form action='/patient_list/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@if ($outpatient_lists->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>MRN</th>
    <th>Patient</th>
    <th>Time</th> 
	<th></th>
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
			<td width='10%'>
					{{ $list->patient_mrn }}
			</td>
			<td>
				{{$list->patient_name}}
			</td>
			<td width='20%'>
					{{ date('d F, H:i', strtotime($list->created_at)) }}
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
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $outpatient_lists->appends(['search'=>$search])->render() }}
	@else
	{{ $outpatient_lists->render() }}
@endif
<br>
@if ($outpatient_lists->total()>0)
	{{ $outpatient_lists->total() }} records found.
@else
	No record found.
@endif
@endsection
