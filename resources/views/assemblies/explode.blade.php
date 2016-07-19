@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Explode Assembly</h1>
<a class="btn btn-default" href="/products/{{ $product->product_code }}/option" role="button">Back</a>
<br>
<br>
@if (Session::has('message'))
    <div class="alert alert-danger">{{ Session::get('message') }}</div>
@endif
<h4>The maximum number of dismantle is <strong>{{ $product->product_on_hand }}</strong></h4>
<br>
<form class='form-inline' action='/explode_assembly/{{ $product->product_code }}' method='post'>
	<label>Quanity</label>
	{{ Form::text('quantity', null, ['class'=>'form-control','placeholder'=>'']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::submit('Submit', ['class'=>'btn btn-default']) }}
</form>
@endsection
