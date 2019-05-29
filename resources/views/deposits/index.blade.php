@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Deposit Transactions
<a href='/deposits/create/{{ $encounter->encounter_id }}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
@if ($deposits->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Method</th>
    <th>Description</th>
    <th>Date</th> 
    <th>Amount</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($deposits as $deposit)
	<tr>
			<td>
					<a href='{{ URL::to('deposits/'. $deposit->deposit_id . '/edit') }}'>
						{{$deposit->payment_name}}
					</a>
			</td>
			<td>
					{{$deposit->deposit_description}}
			</td>
			<td>
					{{ DojoUtility::dateTimeReadFormat($deposit->created_at) }}
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
