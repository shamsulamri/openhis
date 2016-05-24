@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Bill
</h1>
@include('common.errors')
<br>
{{ Form::model($bill, ['route'=>['bills.update',$bill->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bills.bill')
{{ Form::close() }}

@endsection
