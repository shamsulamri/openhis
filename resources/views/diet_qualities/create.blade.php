@extends('layouts.app')

@section('content')
<h1>
New Diet Quality
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_quality, ['url'=>'diet_qualities', 'class'=>'form-horizontal']) }} 
    
	@include('diet_qualities.diet_quality')
{{ Form::close() }}

@endsection
