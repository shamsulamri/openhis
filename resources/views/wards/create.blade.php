@extends('layouts.app')

@section('content')
<h1>
New Ward
</h1>
@include('common.errors')
<br>
{{ Form::model($ward, ['url'=>'wards', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('ward_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>    
    
	@include('wards.ward')
{{ Form::close() }}

@endsection
