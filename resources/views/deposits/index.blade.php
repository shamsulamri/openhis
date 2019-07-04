@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Deposit Transactions
<a href='/deposits/create/{{ $patient->patient_id }}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
@if ($deposits->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Encounter</th>
    <th>Note</th>
    <th>Method</th>
    <th>Amount</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($deposits as $deposit)
	<tr>
			<td>
					{{ DojoUtility::dateReadFormat($deposit->deposit_date) }}
			</td>
			<td>
					@if ($deposit->encounterType)
					{{ $deposit->encounterType->encounter_name }}
					@endif
			</td>
			<td>
					{{$deposit->deposit_description}}
			</td>
			<td>
					<a href='{{ URL::to('deposits/'. $deposit->deposit_id . '/edit') }}'>
						{{$deposit->payment_name}}
					</a>
			</td>
			<td>
					{{ number_format($deposit->deposit_amount,2) }}
			</td>
			<td align='right'>
				<a class='btn btn-default btn-xs'  target="_blank" href='{{ Config::get('host.report_server') }}/ReportServlet?report=deposit_receipt&id={{ $deposit->deposit_id }}'>
				Print Receipt
				</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('deposits/delete/'. $deposit->deposit_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $deposits->appends(['search'=>$search])->render() }}
	@else
	{{ $deposits->render() }}
@endif
<br>
@if ($deposits->total()>0)
	{{ $deposits->total() }} records found.
@else
	No record found.
@endif
@endsection
