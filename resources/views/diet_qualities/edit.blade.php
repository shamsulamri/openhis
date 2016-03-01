@extends('layouts.app')

@section('content')
<h1>
Edit Diet Quality
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_quality, ['route'=>['diet_qualities.update',$diet_quality->qc_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('diet_qualities.diet_quality')
{{ Form::close() }}

@endsection
