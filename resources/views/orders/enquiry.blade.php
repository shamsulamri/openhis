@extends('layouts.app')

@section('content')
<h1>Order Enquiry</h1>
<form id='form' action='/order/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Patient</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-2 control-label'>Ward</label>
						<div class='col-sm-10'>
            				{{ Form::select('ward_code', $ward,$ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-2 control-label'>Physician</label>
						<div class='col-sm-10'>
								{{ Form::select('user_id', $consultants,$user_id, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>From</div></label>
						<div class='col-sm-9'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_start" id="date_start" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_start) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-2 control-label'>To</label>
						<div class='col-sm-10'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_end" id="date_end" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_end) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-2 control-label'>Category</label>
						<div class='col-sm-10'>
								{{ Form::select('category_code', $categories,$category_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Status</div></label>
						<div class='col-sm-9'>
								{{ Form::select('status_code', $status,$status_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-2 control-label'>Age</label>
						<div class='col-sm-10'>
							{{ Form::text('age', $age, ['placeholder'=>'More than specify hours','class'=>'form-control','maxlength'=>'2']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
			</div>
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>


<br>

@if ($orders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter</th>
    <th>Patient</th>
	<th>Order Date</th>
	<th>Product</th>
	<th>Physician</th>
	<th>Age</th>
	<th>Turnaround</th>
	<th>Status</th>
	</tr>
  </thead>
	<tbody>
@foreach ($orders as $order)
	<tr>
			<td>
					{{ $order->encounter_id }}
			</td>
			<td>
					{{$order->patient_name}}
					<br>
					<small>{{$order->patient_mrn}}</small>
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($order->order_date) }}
			</td>
			<td>
					{{$order->product_name}}
					<br>
					<small>{{$order->product_code}}</small>
			</td>
			<td>
					{{ $order->name }}
			</td>
			<td>
					{{ $order->age }}
			</td>
			<td>
					{{ $order->turnaround }}
			</td>
			<td>
					@if ($order->cancel_id) 
							<span class="label label-warning">Cancel</span>
							<br>
							<small>{{$order->cancel_reason}}</small>
					@else
							@if ($order->order_completed==0) 
								@if ($order->post_id==0) 
									<span class="label label-danger">Unposted</span>
								@else
									<span class="label label-default">Posted</span>
								@endif
							@else
								<span class="label label-success">Completed</span>
								@if (!empty($order->report==0))
									<span class="label label-primary">Reported</span>
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
	{{ $orders->appends(['search'=>$search])->render() }}
	@else
	{{ $orders->render() }}
@endif
<br>
@if ($orders->total()>0)
	{{ $orders->total() }} records found.
@else
	No record found.
@endif
<script>
		$('#date_start').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#date_end').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
