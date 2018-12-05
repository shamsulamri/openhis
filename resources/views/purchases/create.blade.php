@extends('layouts.app')

@section('content')
<h1>
New Purchase
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase, ['url'=>'purchases', 'class'=>'form-horizontal']) }} 
    
	@include('purchases.purchase')
{{ Form::close() }}

@endsection
