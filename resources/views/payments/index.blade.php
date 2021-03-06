@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Payment Transactions
<a href='/payments/create/{{ $patient_id }}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
@if ($payments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Bill ID</th> 
    <th>Method</th> 
    <th>Description</th> 
    <th>Date</th>
    <th>Amount</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($payments as $payment)
	<tr>
			<td>
					{{$payment->bill_id}}
			</td>
			<td>
					<a href='{{ URL::to('payments/'. $payment->payment_id . '/edit') }}'>
					{{$payment->payment_name}}
					</a>
			</td>
			<td>
					{{$payment->payment_description}}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($payment->created_at) }}
			</td>
			<td>
					{{ number_format($payment->payment_amount,2) }}
			</td>
			<td align='right'>
<!--
			@if ($payment->encounter_id==0)
					<a class="btn btn-default btn-xs pull-right" href="{{ Config::get('host.report_server') }}?report=receipt&id={{ $payment->payment_id }}" role="button">Print Receipt</a> 
			@endif
-->
				<a class='btn btn-default btn-xs'  target="_blank" href='{{ Config::get('host.report_server') }}?report=payment_receipt&id={{ $payment->payment_id }}'>
				Print Receipt
				</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('payments/delete/'. $payment->payment_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $payments->appends(['search'=>$search])->render() }}
	@else
	{{ $payments->render() }}
@endif
<br>
@if ($payments->total()>0)
	{{ $payments->total() }} records found.
@else
	No record found.
@endif
@endsection
