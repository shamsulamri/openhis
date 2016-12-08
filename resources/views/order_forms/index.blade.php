@extends('layouts.app')

@section('content')
<h1>Order Form List
<a href='/order_forms/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/order_form/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($order_forms->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_forms as $order_form)
	<tr>
			<td>
					<a href='{{ URL::to('order_forms/'. $order_form->form_code . '/edit') }}'>
						{{$order_form->form_name}}
					</a>
			</td>
			<td>
					{{$order_form->form_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_forms/delete/'. $order_form->form_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_forms->appends(['search'=>$search])->render() }}
	@else
	{{ $order_forms->render() }}
@endif
<br>
@if ($order_forms->total()>0)
	{{ $order_forms->total() }} records found.
@else
	No record found.
@endif
@endsection
