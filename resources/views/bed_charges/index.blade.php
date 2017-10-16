@extends('layouts.app')

@section('content')
<h1>Bed Charge Index
<a href='/bed_charges/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bed_charge/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bed_charges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>bed_code</th>
    <th>charge_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_charges as $bed_charge)
	<tr>
			<td>
					<a href='{{ URL::to('bed_charges/'. $bed_charge->charge_id . '/edit') }}'>
						{{$bed_charge->bed_code}}
					</a>
			</td>
			<td>
					{{$bed_charge->charge_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bed_charges/delete/'. $bed_charge->charge_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bed_charges->appends(['search'=>$search])->render() }}
	@else
	{{ $bed_charges->render() }}
@endif
<br>
@if ($bed_charges->total()>0)
	{{ $bed_charges->total() }} records found.
@else
	No record found.
@endif
@endsection
