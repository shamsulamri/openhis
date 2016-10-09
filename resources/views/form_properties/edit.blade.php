@extends('layouts.app')

@section('content')
<h1>
Edit Form Property
</h1>
@include('common.errors')
<br>
{{ Form::model($form_property, ['route'=>['form_properties.update',$form_property->property_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('property_code', $form_property->property_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('form_properties.form_property')
{{ Form::close() }}

@endsection
