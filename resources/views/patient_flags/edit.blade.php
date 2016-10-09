@extends('layouts.app')

@section('content')
<h1>
Edit Patient Flag
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_flag, ['route'=>['patient_flags.update',$patient_flag->flag_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('flag_code', $patient_flag->flag_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('patient_flags.patient_flag')
{{ Form::close() }}

@endsection
