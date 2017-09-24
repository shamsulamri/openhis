@extends('layouts.app')

@section('content')
<h1>
Edit Payment Credit
</h1>
@include('common.errors')
<br>
{{ Form::model($payment_credit, ['route'=>['payment_credits.update',$payment_credit->credit_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('payment_credits.payment_credit')
{{ Form::close() }}

@endsection
