@extends('layouts.app')

@section('content')
<h1>Admission Task List</h1>
<br>
<form action='/admission_task/search' method='post'>
	{{ Form::select('wards', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<br>
	{{ Form::select('categories', $categories, $category, ['class'=>'form-control','maxlength'=>'10']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<br>
	{{ Form::submit('Refresh', ['class'=>'btn btn-primary']) }}
</form>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@if ($admission_tasks->total()>0)
<form action='/admission_task/status' method='post'>
<table class="table table-hover">
 <thead>
	<tr> 
    <th></th>
    <th></th>
    <th>Order</th>
    <th>Patient</th> 
    <th>MRN</th> 
    <th>Bed</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($admission_tasks as $admission_task)
	<tr>
			<td width='10'>
					{{ Form::checkbox($admission_task->order_id, 1, null) }}
			</td>
			<td>
					{{ date('d M, H:i', strtotime($admission_task->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('order_tasks/'. $admission_task->order_id . '/edit') }}'>
						{{$admission_task->product_name}}
					</a>
					@if (!empty($admission_task->drug_strength))
						<br>
						{{ $admission_task->drug_strength }}
						{{ $admission_task->unit_code }}, 
						{{ $admission_task->drug_dosage }}
						{{ $admission_task->dosage_code }},
						{{ $admission_task->route_code }},
						{{ $admission_task->frequency_code }}, 
						{{ $admission_task->drug_period }} 
						{{ $admission_task->period_code }}
					@endif
			</td>
			<td>
					{{$admission_task->patient_name}}
			</td>
			<td>
					{{$admission_task->patient_mrn}}
			</td>
			<td>
					{{$admission_task->bed_name}}
			</td>
			<td width='10' align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('admission_tasks/delete/'. $admission_task->order_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if ($admission_tasks->total()>0)
{{ Form::submit('Log Task', ['class'=>'btn btn-default']) }}
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
