@extends('layouts.app')

@section('content')
<h1>
Edit Patient Type
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_type, ['route'=>['patient_types.update',$patient_type->type_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('type_code', $patient_type->type_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('patient_types.patient_type')
{{ Form::close() }}

@endsection
