@extends('layouts.app')

@section('content')
<?php
$ids='';
?>
<h1>Order Tasks</h1>
<form class='form-inline' action='/admission_task/search' method='post'>
	<label>Location&nbsp;</label>
	{{ Form::select('location_code', $locations, $location_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Ward&nbsp;</label>
	{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Type&nbsp;</label>
	{{ Form::select('categories', $categories, $category, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Group by&nbsp;</label>

	{{ Form::select('group_by', array('order'=>'Order','patient'=>'Patient'),$group_by, ['class'=>'form-control']) }}
	{{ Form::submit('Refresh', ['class'=>'btn btn-default']) }}
&nbsp;
	{{ Form::checkbox('show_all',1, $show_all, ['class'=>'form-control']) }} 
	<label>
	Show All
	</label>

	<input type='hidden' name="_token" value="{{ csrf_token() }}">

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
		@if ($group_by=='order')
		Patient
		@else
		Order
		@endif
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
	@if ($group_by=='order')
			@if ($admission_task->product_code != $header_code)
			@if ($header_count>0)
				<script>
				document.getElementById("{{ $header_code }}").textContent="{{ $header_count }}";
				</script>
			@endif
			<?php $header_count=0; ?>
			<tr>
					<th colspan=8>
						{{strtoupper($admission_task->product_name)}} (<span id='{{ $admission_task->product_code }}'>#</span>)
					</th>
			</tr>
			@endif
	@endif
	@if ($group_by=='patient')
			@if ($admission_task->patient_id != $header_code)
			<?php $header_count=0; ?>
			<tr>
					<th colspan=6>
						<strong>
						{{$admission_task->patient_name}} ({{$admission_task->patient_mrn}})
						</strong>
					</th>
					<th>
						@if ($admission_task->bed_name)
						<br>
						<small>
						{{$admission_task->bed_name}}, {{ $admission_task->ward_name }}
						</small>
						@endif

					</th>

			</tr>
			@endif
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
					{{ Form::checkbox('order:'.$admission_task->order_id, 1, $admission_task->order_completed, ['class'=>'i-checks']) }} 
			</td>
			<td width=10>
				@if ($label)
					<span class="label label-{{ $color }}">
					{{ $label }}
					</span>
				@endif
			</td>
	<!-- Patient -->
	@if ($group_by=='patient')
			<td>
				{{strtoupper($admission_task->product_name)}}<br>
				{{ $order_helper->drugDescription($admission_task->order_id, "") }}
			</td>
			<td>
			</td>
	@endif

	<!-- Order -->
	@if ($group_by=='order')
			<td>
					{{strtoupper($admission_task->patient_name)}}<br>
					{{$admission_task->patient_mrn}}
			</td>
			<td>
					{{ $order_helper->drugDescription($admission_task->order_id, "") }}
			</td>
			<td width=10>
					@if ($admission_task->bed_name)
					&nbsp;{{$admission_task->bed_name}}
					@endif
			</td>
	@endif

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
									@if ($admission_task->product_duration_use)
									<a href="{{ URL::to('order_stop/create/'. $admission_task->order_id . '?source=nurse') }}" class='btn btn-danger btn-xs'>
									Stop
									</a>
									@endif
									@if (empty($admission_task->order_drug_id))
									<a class='btn btn-default btn-xs'  target="_blank" href='{{ Config::get('host.report_server') }}/ReportServlet?report=order_label&id={{ $admission_task->order_id }}'>
										Print Label
									</a>
									@else
									<a class='btn btn-default btn-xs'  target="_blank" href='{{ Config::get('host.report_server') }}/ReportServlet?report=drug_label&id={{ $admission_task->order_id }}'>
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
	@if ($group_by=='order')
	<?php $header_code = $admission_task->product_code ?>
	@endif
	@if ($group_by=='patient')
	<?php $header_code = $admission_task->patient_id ?>
	@endif
@endforeach
			@if ($header_count>0)
				<script>
				document.getElementById("{{ $header_code }}").textContent="{{ $header_count }}";
				</script>
			@endif
@endif
</tbody>
</table>

{{ Form::hidden('group_by', $group_by) }}
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
