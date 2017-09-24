@extends('layouts.app')

@section('content')
<h1>
New Credit Card
</h1>
@include('common.errors')
<br>
{{ Form::model($credit_card, ['url'=>'credit_cards', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('card_code')) has-error @endif'>
        <label for='card_code' class='col-sm-2 control-label'>card_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('card_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('card_code')) <p class="help-block">{{ $errors->first('card_code') }}</p> @endif
        </div>
    </div>    
    
	@include('credit_cards.credit_card')
{{ Form::close() }}

@endsection
