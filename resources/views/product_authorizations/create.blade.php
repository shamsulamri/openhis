@extends('layouts.app')

@section('content')
<h1>
New Product Authorization
</h1>

<br>
{{ Form::model($product_authorization, ['url'=>'product_authorizations', 'class'=>'form-horizontal']) }} 
    
	@include('product_authorizations.product_authorization')
{{ Form::close() }}

@endsection
