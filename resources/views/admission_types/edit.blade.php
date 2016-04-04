@extends('layouts.app')

@section('content')
<h1>
Edit Admission Type
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_type, ['route'=>['admission_types.update',$admission_type->admission_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>admission_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('admission_code', $admission_type->admission_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('admission_types.admission_type')
{{ Form::close() }}

@endsection