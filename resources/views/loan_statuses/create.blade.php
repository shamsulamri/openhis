@extends('layouts.app')

@section('content')
<h1>
New Loan Status
</h1>
@include('common.errors')
<br>
{{ Form::model($loan_status, ['url'=>'loan_statuses', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('loan_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>    
    
	@include('loan_statuses.loan_status')
{{ Form::close() }}

@endsection
