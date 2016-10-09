@extends('layouts.app')

@section('content')
<h1>
Edit Ward Class
</h1>
@include('common.errors')
<br>
{{ Form::model($ward_class, ['route'=>['ward_classes.update',$ward_class->class_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('class_code', $ward_class->class_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('ward_classes.ward_class')
{{ Form::close() }}

@endsection
