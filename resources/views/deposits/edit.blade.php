@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Deposit
</h1>

<br>
{{ Form::model($deposit, ['route'=>['deposits.update',$deposit->deposit_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('deposits.deposit')
{{ Form::close() }}

@endsection
