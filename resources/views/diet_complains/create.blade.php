@extends('layouts.app')

@section('content')
<h1>
New Diet Complain
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_complain, ['url'=>'diet_complains', 'class'=>'form-horizontal']) }} 
	@include('diet_complains.diet_complain')
{{ Form::close() }}

@endsection
