@extends('layouts.app')

@section('content')
<h1>Bill Material Index</h1>
<br>
<form action='/bill_material/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/bill_materials/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($bill_materials->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_code</th>
    <th>id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bill_materials as $bill_material)
	<tr>
			<td>
					<a href='{{ URL::to('bill_materials/'. $bill_material->id . '/edit') }}'>
						{{$bill_material->product_code}}
					</a>
			</td>
			<td>
					{{$bill_material->id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_materials/delete/'. $bill_material->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bill_materials->appends(['search'=>$search])->render() }}
	@else
	{{ $bill_materials->render() }}
@endif
<br>
@if ($bill_materials->total()>0)
	{{ $bill_materials->total() }} records found.
@else
	No record found.
@endif
@endsection
