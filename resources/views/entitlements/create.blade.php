@extends('layouts.app')

@section('content')
<h1>
New Entitlement
</h1>
@include('common.errors')
<br>
{{ Form::model($entitlement, ['url'=>'entitlements', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('entitlement_code')) has-error @endif'>
        <label for='entitlement_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('entitlement_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('entitlement_code')) <p class="help-block">{{ $errors->first('entitlement_code') }}</p> @endif
        </div>
    </div>    
    
	@include('entitlements.entitlement')
{{ Form::close() }}

@endsection
