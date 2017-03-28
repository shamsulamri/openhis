@extends('layouts.app')

@section('content')
<h1>
Edit Form System
</h1>
@include('common.errors')
<br>
{{ Form::model($form_system, ['route'=>['form_systems.update',$form_system->system_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>system_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('system_code', $form_system->system_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('form_systems.form_system')
{{ Form::close() }}

@endsection
