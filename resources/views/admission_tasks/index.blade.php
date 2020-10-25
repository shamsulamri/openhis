@extends('layouts.app')

@section('content')
<?php
$ids='';
?>
<h1>Order Tasks</h1>
<form class='form-horizontal' action='/admission_task/search' method='post'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Location</div></label>
						<div class='col-sm-9'>
							{{ Form::select('location_code', $locations, $location_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Ward</label>
						<div class='col-sm-9'>
							{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Type</label>
						<div class='col-sm-9'>
							{{ Form::select('categories', $categories, $category, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<!--
	{{ Form::select('encounter_code', $encounter_type, $encounter_code, ['id'=>'encounter','class'=>'form-control','onchange'=>'checkTriage()']) }}
	{{ Form::checkbox('show_all',1, $show_all, ['class'=>'form-control']) }} 
	<label>
	Show All
	</label>
	-->

	<input type='hidden' name="_token" value="{{ csrf_token() }}">

	{{ Form::submit('Refresh', ['class'=>'btn btn-default']) }}
</form>
<br>
<br>

@if ($admission_tasks->total()>0)
<form action='/admission_task/status' method='post'>
@if ($admission_tasks->total()>0)
{{ Form::submit('Update Task', ['class'=>'btn btn-primary']) }}
<br>
<br>
@endif
<input type='hidden' name="_token" value="{{ csrf_token() }}">
<table class="table table-hover table-condensed">
<!--
 <thead>
	<tr> 
    <th></th>
    <th>Bed</th> 
    <th>MRN</th> 
	<th>
		Patient
	</th> 
    <th>Date</th>
	<th></th>
	</tr>
  </thead>
-->
	<tbody>
<?php 
$header_code = null;
$header_count=0;
?>
@foreach ($admission_tasks as $admission_task)
	@if ($admission_task->patient_id != $header_code)
	<?php $header_count=0; ?>
	<tr>
			<th colspan=6>
				<strong>
				{{$admission_task->patient_name}} ({{$admission_task->patient_mrn}})
				</strong>
				@if ($admission_task->bed_name)
				<div class='pull-right'>
				{{$admission_task->bed_name}} ({{ $admission_task->ward_name }})
				</div>
				@endif
			</th>
	</tr>
	@endif
<?php
	$ids = $ids.$admission_task->order_id.';';
	$label = '';
	$color = '';
	if ($admission_task->urgency_index==1) $color = 'danger';
	if ($admission_task->urgency_index==2) $color = 'warning';
	if ($admission_task->urgency_name) $label = $admission_task->urgency_name;
	if ($admission_task->frequency_code == 'STAT') {
			$label = 'STAT';
			$color = 'danger';
	}
?>
			<tr>

			<td width=10>
			@if ($admission_task->category_code != 'radiography' && $admission_task->category_code != 'imaging' && $admission_task->category_code != 'lab')
					{{ Form::checkbox('order:'.$admission_task->order_id, 1, $admission_task->order_completed, ['class'=>'i-checks']) }} 
			@endif
			</td>
			<td width=10>
				@if ($label)
					<span class="label label-{{ $color }}">
					{{ $label }}
					</span>
				@endif
			</td>
			<td>
				{{strtoupper($admission_task->product_name)}}<br>
				{{ $admission_task->product_code }}<br>
				{{ $order_helper->drugDescription($admission_task->order_id, "") }}
			</td>
			<td>
				Ordered by {{ $admission_task->order_by }}
				@if (!empty($admission_task->name))
					<br>Done by 
					{{$admission_task->name }}, {{ date('d M H:i', strtotime($admission_task->updated_at)) }}
				@endif
			</td>
			<td align='right'>
					@if ($admission_task->order_drug_id & $admission_task->ward_name)
						<a href='{{ URL::to('medication_record/mar/'. $admission_task->encounter_id) }}' class='btn btn-primary btn-xs'>
						&nbsp;&nbsp;&nbsp;&nbsp; MAR &nbsp;&nbsp;&nbsp;&nbsp;
						</a>
					@endif
				@if (Auth::user()->authorization->module_support==1)
					<a href='{{ URL::to('order_tasks/task/'. $admission_task->encounter_id) .'/'. $location_code }}' class='btn btn-primary btn-xs'>
						Open	
					</a>
				@else
					@if ($admission_task->order_completed==0)
						@if (empty($admission_task->cancel_id))
						<!--
									@if ($admission_task->product_duration_use)
									<a href="{{ URL::to('order_stop/create/'. $admission_task->order_id . '?source=nurse') }}" class='btn btn-danger btn-xs'>
									Stop
									</a>
									@endif
						-->
									@if (empty($admission_task->order_drug_id))
									<a class='btn btn-default btn-xs'  target="_blank" href='{{ Config::get('host.report_server') }}?report=order_label&id={{ $admission_task->order_id }}'>
										Print Label
									</a>
									@else
									<a class='btn btn-default btn-xs'  target="_blank" href='{{ Config::get('host.report_server') }}?report=drug_label&id={{ $admission_task->order_id }}'>
										Print Label
									</a>
									@endif
									<!--
									<a href="{{ URL::to('task_cancellations/create/'. $admission_task->order_id . '?source=nurse') }}" class='btn btn-warning btn-xs'>
									&nbsp;&nbsp;&nbsp;Cancel&nbsp;&nbsp;&nbsp;
									</a>
									-->
						@endif
					@endif
				@endif
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('admission_tasks/delete/'. $admission_task->order_id) }}'>Delete</a>
					@endcan
			</td>
	</tr>
	<?php $header_count+=1; ?>
	<?php $header_code = $admission_task->patient_id ?>
@endforeach
			@if ($header_count>0)
				<script>
				document.getElementById("{{ $header_code }}").textContent="{{ $header_count }}";
				</script>
			@endif
@endif
</tbody>
</table>

{{ Form::hidden('show_all', $show_all) }}
@if ($ids)
{{ Form::hidden('ids', $ids) }}
@endif

@if (Auth::user()->authorization->module_support==1)
		{{ Form::hidden('ward_code', $ward_code) }}
@else
		{{ Form::hidden('categories', $category) }}
@endif

</form>
@if (isset($search)) 
	{{ $admission_tasks->appends(['search'=>$search])->render() }}
	@else
	{{ $admission_tasks->render() }}
@endif
<br>
@if ($admission_tasks->total()>0)
	{{ $admission_tasks->total() }} records found.
@else
	No record found.
@endif

@endsection
