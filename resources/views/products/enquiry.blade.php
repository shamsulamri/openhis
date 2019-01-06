@extends('layouts.app')

@section('content')
<h1>Product Enquiry</h1>
<form action='/products/enquiry' method='post' class='form-horizontal'>

	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Product</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Enter product code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Store</label>
						<div class='col-sm-9'>
							{{ Form::select('store_code', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<button type="submit" class="btn btn-md btn-primary">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
<br>
@include('products.product_enquiry')
@endsection
