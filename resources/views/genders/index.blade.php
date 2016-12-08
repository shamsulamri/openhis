@extends('layouts.app')

@section('content')
<h1>Gender List
<a href='/genders/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/gender/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($genders->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($genders as $gender)
	<tr>
			<td>
					<a href='{{ URL::to('genders/'. $gender->gender_code . '/edit') }}'>
						{{$gender->gender_name}}
					</a>
			</td>
			<td>
					{{$gender->gender_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('genders/delete/'. $gender->gender_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $genders->appends(['search'=>$search])->render() }}
	@else
	{{ $genders->render() }}
@endif
<br>
@if ($genders->total()>0)
	{{ $genders->total() }} records found.
@else
	No record found.
@endif
@endsection
