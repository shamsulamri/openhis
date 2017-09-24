@extends('layouts.app')

@section('content')
<h1>Payment Credit Index
<a href='/payment_credits/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/payment_credit/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($payment_credits->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>card_code</th>
    <th>credit_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($payment_credits as $payment_credit)
	<tr>
			<td>
					<a href='{{ URL::to('payment_credits/'. $payment_credit->credit_id . '/edit') }}'>
						{{$payment_credit->card_code}}
					</a>
			</td>
			<td>
					{{$payment_credit->credit_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('payment_credits/delete/'. $payment_credit->credit_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $payment_credits->appends(['search'=>$search])->render() }}
	@else
	{{ $payment_credits->render() }}
@endif
<br>
@if ($payment_credits->total()>0)
	{{ $payment_credits->total() }} records found.
@else
	No record found.
@endif
@endsection
