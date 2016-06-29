@extends('layouts.app')

@section('content')
<h1>
New Tax Code
</h1>
@include('common.errors')
<br>
{{ Form::model($tax_code, ['url'=>'tax_codes', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('tax_code')) has-error @endif'>
        <label for='tax_code' class='col-sm-2 control-label'>tax_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('tax_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('tax_code')) <p class="help-block">{{ $errors->first('tax_code') }}</p> @endif
        </div>
    </div>    
    
	@include('tax_codes.tax_code')
{{ Form::close() }}

@endsection