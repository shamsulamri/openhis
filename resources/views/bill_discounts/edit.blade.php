@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Edit Bill Discount
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_discount, ['route'=>['bill_discounts.update',$bill_discount->discount_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bill_discounts.bill_discount')
{{ Form::close() }}

@endsection
