@extends('layouts.app')

@section('content')
<h1>
Edit Drug System
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_system, ['route'=>['drug_systems.update',$drug_system->system_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>system_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('system_code', $drug_system->system_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_systems.drug_system')
{{ Form::close() }}

@endsection
