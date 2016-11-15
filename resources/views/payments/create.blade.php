@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
New Payment 
</h1>
<br>

{{ Form::model($payment, ['url'=>'payments', 'class'=>'form-horizontal']) }} 
    
	@include('payments.payment')
{{ Form::close() }}

@endsection
