@extends('layouts.app')

@section('content')
<h1>
Edit Purchase
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase, ['route'=>['purchases.update',$purchase->purchase_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('purchases.purchase')
{{ Form::close() }}

@endsection
