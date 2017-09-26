@extends('layouts.app')

@section('content')
<h1>
Edit Diet Census
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_census, ['route'=>['diet_censuses.update',$diet_census->census_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('diet_censuses.diet_census')
{{ Form::close() }}

@endsection
