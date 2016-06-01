@extends('layouts.app')

@section('content')
<h1>
Edit Bill
</h1>
@include('common.errors')
<br>
{{ Form::model($bill, ['route'=>['bills.update',$bill->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bills.bill')
{{ Form::close() }}

@endsection
