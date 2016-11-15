@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Collection
</h1>

<br>
{{ Form::model($payment, ['route'=>['payments.update',$payment->payment_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('payments.payment')
{{ Form::close() }}

@endsection
