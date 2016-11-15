@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
New Deposit
</h1>

<br>
{{ Form::model($deposit, ['url'=>'deposits', 'class'=>'form-horizontal']) }} 
    
	@include('deposits.deposit')
{{ Form::close() }}

@endsection
