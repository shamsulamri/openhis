@extends('layouts.app')

@section('content')
<h1>Refund Index
<a href='/refunds/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/refund/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($refunds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>refund_type</th>
    <th>refund_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($refunds as $refund)
	<tr>
			<td>
					<a href='{{ URL::to('refunds/'. $refund->refund_id . '/edit') }}'>
						{{$refund->refund_type}}
					</a>
			</td>
			<td>
					{{$refund->refund_id}}
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
