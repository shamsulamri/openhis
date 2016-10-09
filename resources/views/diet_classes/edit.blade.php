@extends('layouts.app')

@section('content')
<h1>
Edit Diet Class
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_class, ['route'=>['diet_classes.update',$diet_class->class_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('class_code', $diet_class->class_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_classes.diet_class')
{{ Form::close() }}

@endsection
