@extends('layouts.app')

@section('content')
<h1>
New Payment Credit
</h1>
@include('common.errors')
<br>
{{ Form::model($payment_credit, ['url'=>'payment_credits', 'class'=>'form-horizontal']) }} 
    
	@include('payment_credits.payment_credit')
{{ Form::close() }}

@endsection
