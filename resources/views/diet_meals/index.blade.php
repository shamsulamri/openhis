@extends('layouts.app')

@section('content')
<h1>Diet Meal List
<a href='/diet_meals/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_meal/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($diet_meals->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_meals as $diet_meal)
	<tr>
			<td>
					<a href='{{ URL::to('diet_meals/'. $diet_meal->meal_code . '/edit') }}'>
						{{$diet_meal->meal_name}}
					</a>
			</td>
			<td>
					{{$diet_meal->meal_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_meals/delete/'. $diet_meal->meal_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_meals->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_meals->render() }}
@endif
<br>
@if ($diet_meals->total()>0)
	{{ $diet_meals->total() }} records found.
@else
	No record found.
@endif
@endsection
