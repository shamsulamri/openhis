@extends('layouts.app')

@section('content')
<h1>Payment Method Index
<a href='/payment_methods/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/payment_method/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
