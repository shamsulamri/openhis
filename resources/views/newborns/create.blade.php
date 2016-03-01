@extends('layouts.app')

@section('content')
<h1>
New Newborn
</h1>
@include('common.errors')
<br>
{{ Form::model($newborn, ['url'=>'newborns', 'class'=>'form-horizontal']) }} 
    
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
