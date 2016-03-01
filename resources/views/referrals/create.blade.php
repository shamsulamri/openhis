@extends('layouts.app')

@section('content')
<h1>
New Referral
</h1>
@include('common.errors')
<br>
{{ Form::model($referral, ['url'=>'referrals', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('referral_code')) has-error @endif'>
        <label for='referral_code' class='col-sm-2 control-label'>referral_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('referral_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('referral_code')) <p class="help-block">{{ $errors->first('referral_code') }}</p> @endif
        </div>
    </div>    
    
	@include('referrals.referral')
{{ Form::close() }}

@endsection
