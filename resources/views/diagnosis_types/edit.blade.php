@extends('layouts.app')

@section('content')
<h1>
Edit Diagnosis Type
</h1>
@include('common.errors')
<br>
{{ Form::model($diagnosis_type, ['route'=>['diagnosis_types.update',$diagnosis_type->type_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('type_code', $diagnosis_type->type_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diagnosis_types.diagnosis_type')
{{ Form::close() }}

@endsection
