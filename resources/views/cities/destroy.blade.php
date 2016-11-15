@extends('layouts.app')

@section('content')
<h1>
Delete City
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $city->city_name }}
{{ Form::open(['url'=>'cities/'.$city->city_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/cities" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
