@extends('layouts.app')

@section('content')
<h1>Diet Therapeutic List
<a href='/diet_therapeutics/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_therapeutic/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($diet_therapeutics->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Name</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_therapeutics as $diet_therapeutic)
	<tr>
			<td>
					<a href='{{ URL::to('diet_therapeutics/'. $diet_therapeutic->therapeutic_code . '/edit') }}'>
						{{$diet_therapeutic->therapeutic_code}}
					</a>
			</td>
			<td>
					{{$diet_therapeutic->therapeutic_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_therapeutics/delete/'. $diet_therapeutic->therapeutic_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_therapeutics->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_therapeutics->render() }}
@endif
<br>
@if ($diet_therapeutics->total()>0)
	{{ $diet_therapeutics->total() }} records found.
@else
	No record found.
@endif
@endsection
