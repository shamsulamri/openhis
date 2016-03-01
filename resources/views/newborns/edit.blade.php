@extends('layouts.app')

@section('content')
<h1>
Edit Newborn
</h1>
@include('common.errors')
<br>
{{ Form::model($newborn, ['route'=>['newborns.update',$newborn->newborn_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
