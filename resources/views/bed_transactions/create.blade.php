@extends('layouts.app')

@section('content')
<h1>
New Bed Transaction
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_transaction, ['url'=>'bed_transactions', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('transaction_code')) has-error @endif'>
        <label for='transaction_code' class='col-sm-2 control-label'>transaction_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('transaction_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('transaction_code')) <p class="help-block">{{ $errors->first('transaction_code') }}</p> @endif
        </div>
    </div>    
    
	@include('bed_transactions.bed_transaction')
{{ Form::close() }}

@endsection
