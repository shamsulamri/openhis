@extends('layouts.app')

@section('content')
<h1>Payment Index</h1>
<br>
<form action='/payment/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/payments/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($payments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>encounter_id</th>
    <th>payment_id</th> 
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
			<td>
					{{$payment->payment_id}}
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
