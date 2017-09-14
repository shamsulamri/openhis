@extends('layouts.app')

@section('content')
<h1>Loan Type Index
<a href='/loan_types/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/loan_type/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($loan_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>type_name</th>
    <th>type_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($loan_types as $loan_type)
	<tr>
			<td>
					<a href='{{ URL::to('loan_types/'. $loan_type->type_code . '/edit') }}'>
						{{$loan_type->type_name}}
					</a>
			</td>
			<td>
					{{$loan_type->type_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('loan_types/delete/'. $loan_type->type_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $loan_types->appends(['search'=>$search])->render() }}
	@else
	{{ $loan_types->render() }}
@endif
<br>
@if ($loan_types->total()>0)
	{{ $loan_types->total() }} records found.
@else
	No record found.
@endif
@endsection
