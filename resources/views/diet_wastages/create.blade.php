@extends('layouts.app')

@section('content')
<h1>
New Diet Wastage
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_wastage, ['url'=>'diet_wastages', 'class'=>'form-horizontal']) }} 
    
	@include('diet_wastages.diet_wastage')
{{ Form::close() }}

@endsection
