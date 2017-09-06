@extends('layouts.app')

@section('content')
<h1>Product Enquiry</h1>
<form action='/products/enquiry' method='post' class='form-inline'>
		<input type='text' class='form-control' placeholder="Enter product code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<label>Store</label>
		{{ Form::select('store_code', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
		<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
<br>
@include('products.product_enquiry')
@endsection
