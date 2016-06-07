@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Payment Collection</h1>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/patients/{{ $patient_id }}' class='btn btn-default'>Return</a>
<a href='/payments/create/{{ $patient_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($payments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter Id</th>
    <th>Encounter Date</th>
    <th>Amount</th> 
    <th>Description</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($payments as $payment)
	<tr>
			<td>
					<a href='{{ URL::to('payments/'. $payment->payment_id . '/edit') }}'>
						{{$payment->encounter_id}}
					</a>
			</td>
			<td width='200'>
					{{ date('d F Y, H:i', strtotime($payment->encounter_date)) }}
			</td>
			<td>
					{{$payment->payment_amount}}
			</td>
			<td>
					{{$payment->payment_description}}
			</td>
			<td align='right'>
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
