@extends('layouts.app')

@section('content')
<h1>Delivery Mode List</h1>
<br>
<form action='/delivery_mode/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/delivery_modes/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($delivery_modes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($delivery_modes as $delivery_mode)
	<tr>
			<td>
					<a href='{{ URL::to('delivery_modes/'. $delivery_mode->delivery_code . '/edit') }}'>
						{{$delivery_mode->delivery_name}}
					</a>
			</td>
			<td>
					{{$delivery_mode->delivery_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('delivery_modes/delete/'. $delivery_mode->delivery_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $delivery_modes->appends(['search'=>$search])->render() }}
	@else
	{{ $delivery_modes->render() }}
@endif
<br>
@if ($delivery_modes->total()>0)
	{{ $delivery_modes->total() }} records found.
@else
	No record found.
@endif
@endsection
