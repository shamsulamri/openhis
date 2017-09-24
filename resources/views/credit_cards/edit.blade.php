@extends('layouts.app')

@section('content')
<h1>
Edit Credit Card
</h1>
@include('common.errors')
<br>
{{ Form::model($credit_card, ['route'=>['credit_cards.update',$credit_card->card_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>card_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('card_code', $credit_card->card_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('credit_cards.credit_card')
{{ Form::close() }}

@endsection
