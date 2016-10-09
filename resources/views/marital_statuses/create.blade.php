@extends('layouts.app')

@section('content')
<h1>
New Marital Status
</h1>
@include('common.errors')
<br>
{{ Form::model($marital_status, ['url'=>'marital_statuses', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('marital_code')) has-error @endif'>
        <label for='marital_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('marital_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('marital_code')) <p class="help-block">{{ $errors->first('marital_code') }}</p> @endif
        </div>
    </div>    
    
	@include('marital_statuses.marital_status')
{{ Form::close() }}

@endsection
