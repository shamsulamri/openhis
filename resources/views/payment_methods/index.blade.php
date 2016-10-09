@extends('layouts.app')

@section('content')
<h1>Payment Method Index</h1>
<br>
<form action='/payment_method/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/payment_methods/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($payment_methods->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($payment_methods as $payment_method)
	<tr>
			<td>
					<a href='{{ URL::to('payment_methods/'. $payment_method->payment_code . '/edit') }}'>
						{{$payment_method->payment_name}}
					</a>
			</td>
			<td>
					{{$payment_method->payment_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('payment_methods/delete/'. $payment_method->payment_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $payment_methods->appends(['search'=>$search])->render() }}
	@else
	{{ $payment_methods->render() }}
@endif
<br>
@if ($payment_methods->total()>0)
	{{ $payment_methods->total() }} records found.
@else
	No record found.
@endif
@endsection
