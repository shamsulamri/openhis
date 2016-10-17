@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Payment Collection</h1>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/patients/{{ $patient_id }}' class='btn btn-default'>Return</a>
<a href='/payments/create/{{ $patient_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($payments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Amount</th> 
    <th>Method</th> 
    <th>Description</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($payments as $payment)
	<tr>
			<td>
					<a href='{{ URL::to('payments/'. $payment->payment_id . '/edit') }}'>
						{{ date('d F Y, H:i', strtotime($payment->created_at)) }}
					</a>
			</td>
			<td>
					{{ number_format($payment->payment_amount,2) }}
			</td>
			<td>
					{{$payment->payment_name}}
			</td>
			<td>
					{{$payment->payment_description}}
			</td>
			@if ($payment->encounter_id==0)
			<td align='right'>
					<a class="btn btn-default btn-xs pull-right" href="{{ Config::get('host.report_server') }}/ReportServlet?report=receipt&id={{ $payment->payment_id }}" role="button">Print Receipt</a> 
			</td>
			@endif
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('payments/delete/'. $payment->payment_id) }}'>Delete</a>
			</td>
			@endcan
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
