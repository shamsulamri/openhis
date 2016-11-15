@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Bill
</h1>

<br>
{{ Form::model($bill, ['route'=>['bill_items.update',$bill->bill_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bill_items.bill')
{{ Form::close() }}

@endsection
