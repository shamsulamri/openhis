@extends('layouts.app')

@section('content')
<h1>Admission Tasks</h1>
<h3>{{ $ward->ward_name }}</h3>
<br>
<form class='form-inline' action='/admission_task/search' method='post'>
	<label>Type&nbsp;</label>
	{{ Form::select('categories', $categories, $category, ['class'=>'form-control','maxlength'=>'10']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<label>Group by&nbsp;</label>

	{{ Form::select('group_by', array('order'=>'Order','patient'=>'Patient'),$group_by, ['class'=>'form-control']) }}
	{{ Form::submit('Refresh', ['class'=>'btn btn-default']) }}
&nbsp;
	{{ Form::checkbox('show_all',1, $show_all, ['class'=>'form-control']) }} 
	<label>
	Show All
	</label>


</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($admission_tasks->total()>0)
<form action='/admission_task/status' method='post'>
@if ($admission_tasks->total()>0)
{{ Form::submit('Update Task', ['class'=>'btn btn-default']) }}
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
					<th colspan=7>
						{{strtoupper($admission_task->product_name)}} (<span id='{{ $admission_task->product_code }}'>#</span>)
					</th>
			</tr>
			@endif
	@endif
	@if ($group_by=='patient')
			@if ($admission_task->patient_id != $header_code)
			<?php $header_count=0; ?>
			<tr>
					<th colspan=4>
						{{$admission_task->bed_name}} : 
						{{$admission_task->patient_name}} ({{$admission_task->patient_mrn}})
					</th>
			</tr>
			@endif
	@endif
	<tr>
			<td width='10'>
					{{ Form::checkbox($admission_task->order_id, 1, $admission_task->order_completed) }}
			</td>
			@if ($group_by=='order')
			<td width='150'>
					{{$admission_task->bed_name}}
			</td>
			<td width='30'>
					{{$admission_task->patient_mrn}}
			</td>
			@endif
			<td>
				@if ($group_by=='patient')
					{{strtoupper($admission_task->product_name)}}
				@else
					<a href='{{ URL::to('admission_tasks/'. $admission_task->order_id . '/edit') }}' >
					{{strtoupper($admission_task->patient_name)}}
					</a>
				@endif
			</td>
			<td>
				@if (!empty($admission_task->name))
					{{$admission_task->name }},	{{ date('d M, H:i', strtotime($admission_task->updated_at)) }}
				@endif
			</td>
			<td width='100' align='right'>
					@if ($admission_task->order_completed==0)
					<a href='{{ URL::to('task_cancellations/create/'. $admission_task->order_id . '?source=nurse') }}' class='btn btn-warning btn-xs'>
					Cancel
					</a>
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
@if ($admission_tasks->total()>0)
{{ Form::submit('Update Task', ['class'=>'btn btn-default']) }}
{{ Form::hidden('completed_ids',$order_ids) }}
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
