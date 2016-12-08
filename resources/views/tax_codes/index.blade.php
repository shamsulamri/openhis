@extends('layouts.app')

@section('content')
<h1>Tax Code Index
<a href='/tax_codes/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/tax_code/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($tax_codes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($tax_codes as $tax_code)
	<tr>
			<td>
					<a href='{{ URL::to('tax_codes/'. $tax_code->tax_code . '/edit') }}'>
						{{$tax_code->tax_name}}
					</a>
			</td>
			<td>
					{{$tax_code->tax_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('tax_codes/delete/'. $tax_code->tax_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $tax_codes->appends(['search'=>$search])->render() }}
	@else
	{{ $tax_codes->render() }}
@endif
<br>
@if ($tax_codes->total()>0)
	{{ $tax_codes->total() }} records found.
@else
	No record found.
@endif
@endsection
