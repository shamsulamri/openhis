@extends('layouts.app')

@section('content')
<h1>
New Bill
</h1>
@include('common.errors')
<br>
{{ Form::model($bill, ['url'=>'bills', 'class'=>'form-horizontal']) }} 
    
	@include('bills.bill')
{{ Form::close() }}

@endsection
