@extends('layouts.app')

@section('content')
<h1>Request List</h1>
<form class='form-horizontal' action='/loan/search' method='post'>
	<div class="row">
			<div class="col-xs-3">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Status</div></label>
						<div class='col-sm-9'>
							{{ Form::select('loan_code', $loan_status, $loan_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Location</label>
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
			<div class="col-xs-1">
					<div class='form-group'>
						<div class='col-sm-12'>
							{{ Form::submit('Refresh', ['class'=>'btn btn-primary pull-right']) }}
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	@if ($is_folder)
	<input type='hidden' name='type' value='folder'>
	@endif
</form>
<br>

@if ($loans->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
	@if ($is_folder)
    <th>Name</th>
    <th>MRN</th>
	@else
    <th>Item</th>
    <th>Code</th>
    <th>Quantity</th> 
	@endif
    <th>Source</th> 
    <th>Request Date</th> 
    <th>Status</th> 
    <th>Type</th> 
    <th>Resolution</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($loans as $loan)
	<tr>
			@if ($is_folder)
			<td>
					<a href='{{ URL::to('loans/'. $loan->loan_id . '/edit') }}'>
					{{ $loan->patient->patient_name }}
					</a>
			</td>
			<td>
						{{ DojoUtility::formatMRN($loan->item_code) }}
			</td>
			@else
			<td>
					<a href='{{ URL::to('loans/'. $loan->loan_id . '/edit') }}'>
						{{ $loan->product_name }}
					</a>
			</td>
			<td>
					{{ $loan->item_code }}
			</td>
			<td>
					@if ($loan->loan_quantity>0)
					{{$loan->loan_quantity }}
					@else
					-
					@endif
			</td>
			@endif
			<td>
					@if ($loan->location)
					{{$loan->location->location_name }}
					@endif
					@if ($loan->ward)
					{{$loan->ward->ward_name }}
					@endif
			</td>
			<td>
					{{ (DojoUtility::dateTimeReadFormat($loan->request_date )) }}
			</td>
			<td>
					{{$loan->status->loan_name}}
					@if ($loan->exchange_id>0)
						(Exchange)
					@endif
			</td>
			<td>
					{{$loan->type->type_name}}
			</td>
			<td>
					@if ($loan->loan_code=='exchanged') 
						{{ (DojoUtility::dateLongFormat($loan->loan_closure_datetime )) }}
					@endif
					@if ($loan->loan_code=='return')
							@if (!empty($loan->loan_closure_datetime))
								{{ (DojoUtility::dateLongFormat($loan->loan_closure_datetime )) }}
							@endif
					@endif
					@if ($loan->loan_code=='on_loan')
							@if (!empty($loan->loan_date_start))
							{{ (DojoUtility::dateLongFormat($loan->loan_date_start )) }}
							@endif
							@if (!empty($loan->loan_date_end))
							- {{ (DojoUtility::dateLongFormat($loan->loan_date_end )) }}
							@endif
					@endif
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('loans/delete/'. $loan->loan_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $loans->appends(['search'=>$search])->render() }}
	@else
	{{ $loans->render() }}
@endif
<br>
@if ($loans->total()>0)
	{{ $loans->total() }} records found.
@else
	No record found.
@endif
@endsection
