@extends('layouts.app')

@section('content')
<h1>
Delete Diet Meal
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_meal->meal_name }}
{{ Form::open(['url'=>'diet_meals/'.$diet_meal->meal_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_meals" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
