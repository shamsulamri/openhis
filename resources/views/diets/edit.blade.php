@extends('layouts.app')

@section('content')
<h1>
Edit Diet
</h1>
@include('common.errors')
<br>
{{ Form::model($diet, ['route'=>['diets.update',$diet->diet_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>diet_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('diet_code', $diet->diet_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diets.diet')
{{ Form::close() }}

@endsection
