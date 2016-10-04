@extends('layouts.app')

@section('content')
<h1>
Edit Encounter Type
</h1>
@include('common.errors')
<br>
{{ Form::model($encounter_type, ['route'=>['encounter_types.update',$encounter_type->encounter_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('encounter_code', $encounter_type->encounter_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('encounter_types.encounter_type')
{{ Form::close() }}

@endsection
