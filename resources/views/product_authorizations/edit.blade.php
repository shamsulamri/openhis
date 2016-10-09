@extends('layouts.app')

@section('content')
<h1>
Edit Product Authorization
</h1>
@include('common.errors')
<br>
{{ Form::model($product_authorization, ['route'=>['product_authorizations.update',$product_authorization->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('product_authorizations.product_authorization')
{{ Form::close() }}

@endsection
