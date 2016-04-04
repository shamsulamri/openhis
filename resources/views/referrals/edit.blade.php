@extends('layouts.app')

@section('content')
<h1>
Edit Referral
</h1>
@include('common.errors')
<br>
{{ Form::model($referral, ['route'=>['referrals.update',$referral->referral_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>referral_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('referral_code', $referral->referral_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('referrals.referral')
{{ Form::close() }}

@endsection