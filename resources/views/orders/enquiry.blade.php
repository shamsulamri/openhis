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
					<div class='form-group'>
						<label class='col-sm-2 control-label'>Id</label>
						<div class='col-sm-10'>
							{{ Form::text('encounter_id', $encounter_id, ['placeholder'=>'Encounter Id','class'=>'form-control','maxlength'=>'2']) }}
						</div>
					</div>
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
    <th>EID</th>
    <th>Patient</th>
    <th>Type</th>
	<th>Product</th>
	<th>Units</th>
	<th>Cost</th>
	<th>Price</th>
	<th>Ordered By</th>
	<th>Consultation Date</th>
	<th>Completed By</th>
	<th>Completed Date</th>
	<!--
	<th>Age</th>
	<th>Turnaround</th>
	-->
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
					{{ $order->type_name }}
			</td>
			<td>
					{{$order->product_name}}
					<br>
					<small>{{$order->product_code}}</small>
					@if ($order->cancel_id) 
							<br>
							<strong>
							<small>Reason: {{$order->cancel_reason}}</small>
							</strong>
					@endif
			</td>
			<td>
					{{ $order->order_quantity_supply }}
			</td>
			<td>
					{{ number_format($order->inv_unit_cost,2) }}
			</td>
			<td>
					{{ number_format($order->order_unit_price,2) }}
			</td>
			<td>
					{{ $order->name }}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($order->consultation_date) }}
			</td>
			<td>
					{{ $order->completed_name }}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($order->completed_at) }}
			</td>
			<!--
			<td>
					{{ $order->age }}
			</td>
			<td>
					{{ $order->turnaround }}
			</td>
			-->
			<td>
					@if ($order->cancel_id) 
							<span class="label label-warning">Cancel</span>
					@else
							@if ($order->order_completed==0) 
								@if ($order->post_id==0) 
									<span class="label label-danger">Unposted</span>
								@else
									<span class="label label-default">Posted</span>
								@endif
							@else
								<span class="label label-success">Completed</span>
								@if (!empty($order->order_report))
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
{{ $orders->appends([
		'search'=>$search,
		'ward_code'=>$ward_code, 
		'user_id'=>$user_id,
		'date_start'=>$date_start, 
		'date_end'=>$date_end, 
		'category_code'=>$category_code,
		'age'=>$age,
		'encounter_id'=>$encounter_id,
		'status_code'=>$status_code,
])->render() }}
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
