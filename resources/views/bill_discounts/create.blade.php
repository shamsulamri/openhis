@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Bill Discount
</h1>
@include('common.errors')
{{ Form::model($bill_discount, ['url'=>'bill_discounts', 'class'=>'form-horizontal']) }} 
    
	@include('bill_discounts.bill_discount')
{{ Form::close() }}

@endsection
