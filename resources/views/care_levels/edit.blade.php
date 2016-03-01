@extends('layouts.app')

@section('content')
<h1>
Edit Care Level
</h1>
@include('common.errors')
<br>
{{ Form::model($care_level, ['route'=>['care_levels.update',$care_level->care_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>care_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('care_code', $care_level->care_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('care_levels.care_level')
{{ Form::close() }}

@endsection
