@extends('layouts.app')

@section('content')
@include('patients.label')
<h2>
Edit Newborn 
</h2>
@include('common.errors')
<br>
{{ Form::model($newborn, ['route'=>['newborns.update',$newborn->newborn_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
