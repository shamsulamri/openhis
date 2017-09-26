@extends('layouts.app')

@section('content')
<h1>
New Diet Census
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_census, ['url'=>'diet_censuses', 'class'=>'form-horizontal']) }} 
    
	@include('diet_censuses.diet_census')
{{ Form::close() }}

@endsection
