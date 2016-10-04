@extends('layouts.app')

@section('content')
<h1>
New Referral
</h1>
@include('common.errors')
<br>
{{ Form::model($referral, ['url'=>'referrals', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('referral_code')) has-error @endif'>
        <label for='referral_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('referral_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('referral_code')) <p class="help-block">{{ $errors->first('referral_code') }}</p> @endif
        </div>
    </div>    
    
	@include('referrals.referral')
{{ Form::close() }}

@endsection
