@extends('layouts.app')

@section('content')
<h1>
New Employer
</h1>
@include('common.errors')
<br>
{{ Form::model($employer, ['url'=>'employers', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('employer_code')) has-error @endif'>
        <label for='employer_code' class='col-sm-3 control-label'>employer_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('employer_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('employer_code')) <p class="help-block">{{ $errors->first('employer_code') }}</p> @endif
        </div>
    </div>    
    
	@include('employers.employer')
{{ Form::close() }}

@endsection
