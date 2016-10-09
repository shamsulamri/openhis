@extends('layouts.app')

@section('content')
<h1>Diet Meal List</h1>
<br>
<form action='/diet_meal/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diet_meals/create' class='btn btn-primary'>Create</a>
<br>
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
