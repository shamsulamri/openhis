@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Refund Transactions
<a href='/refunds/create/{{ $patient->patient_id }}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>

@if ($refunds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Refund Type</th>
    <th>Reference</th> 
    <th>Date</th> 
    <th>Amount</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($refunds as $refund)
	<tr>
			<td>
					<a href='{{ URL::to('refunds/'. $refund->refund_id . '/edit') }}'>
					@if ($refund->refund_type==1)
						Bill
					@else
						Deposit
					@endif
					</a>
			</td>
			<td>
					{{$refund->refund_reference?:"-"}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($refund->created_at) }}
			</td>
			<td>
					{{ number_format($refund->refund_amount,2) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('refunds/delete/'. $refund->refund_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $refunds->appends(['search'=>$search])->render() }}
	@else
	{{ $refunds->render() }}
@endif
<br>
@if ($refunds->total()>0)
	{{ $refunds->total() }} records found.
@else
	No record found.
@endif
@endsection
