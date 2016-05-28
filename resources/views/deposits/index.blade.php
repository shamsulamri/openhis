@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Deposit Collections</h1>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/deposits/create/{{ $encounter->encounter_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($deposits->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Payment Method</th>
    <th>Amount</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($deposits as $deposit)
	<tr>
			<td>
					{{ date('d F Y', strtotime($deposit->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('deposits/'. $deposit->deposit_id . '/edit') }}'>
						{{$deposit->encounter_id}}
					</a>
			</td>
			<td>
					{{$deposit->deposit_amount}}
			</td>
			<td align='right'>
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
