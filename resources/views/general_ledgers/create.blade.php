@extends('layouts.app')

@section('content')
<h1>
New General Ledger
</h1>
@include('common.errors')
<br>
{{ Form::model($general_ledger, ['url'=>'general_ledgers', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('gl_code')) has-error @endif'>
        <label for='gl_code' class='col-sm-2 control-label'>gl_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('gl_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('gl_code')) <p class="help-block">{{ $errors->first('gl_code') }}</p> @endif
        </div>
    </div>    
    
	@include('general_ledgers.general_ledger')
{{ Form::close() }}

@endsection
