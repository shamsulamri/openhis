@extends('layouts.app')

@section('content')
<h1>
Edit Diet Wastage
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_wastage, ['route'=>['diet_wastages.update',$diet_wastage->waste_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('diet_wastages.diet_wastage')
{{ Form::close() }}

@endsection
