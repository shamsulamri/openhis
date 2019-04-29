@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Explode Assembly</h1>
<br>
@if (count($boms)>0) 
		@if (Session::has('message'))
			<div class="alert alert-danger">{{ Session::get('message') }}</div>
		@endif

		<form class='form-horizontal' action='/explode_assembly/refresh/{{ $product->product_code }}' method='post' id='form_search'>
			<div class="row">
					<div class="col-xs-6">
							<div class='form-group'>
								<label class='col-sm-5 control-label'><div align='left'>Store</div></label>
								<div class='col-sm-7'>
									{{ Form::select('store_code', $store,$store_code, ['class'=>'form-control','onchange'=>'refreshStore()']) }}
								</div>
							</div>
					</div>
			</div>
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
		</form>


		@if ($on_hand>0)
		<form class='form-horizontal' action='/explode_assembly/{{ $product->product_code }}' method='post'>

			<div class="row">
					<div class="col-xs-6">
							<div class='form-group'>
								<label class='col-sm-5 control-label'><div align='left'>Quantity On Hand</div></label>
								<div class='col-sm-7'>
									{{ Form::label('max', $on_hand, ['class'=>'form-control','placeholder'=>'']) }}
								</div>
							</div>
					</div>
			</div>
			<div class="row">
					<div class="col-xs-6">
							<div class='form-group'>
								<label class='col-sm-5 control-label'><div align='left'>Quantity to dismantle</div></label>
								<div class='col-sm-7'>
									{{ Form::text('quantity', null, ['class'=>'form-control','placeholder'=>'']) }}
									<br>
									{{ Form::submit('Submit', ['class'=>'btn btn-default']) }}
								</div>
							</div>
					</div>
			</div>

			{{ Form::hidden('store_code', $store_code) }}
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
		</form>
		@else
		<h4>No product to dismantle</h4>
		@endif
@else
<h4>No product to dismantle</h4>
@endif
<script>
	function refreshStore() {
			document.forms['form_search'].submit();
	}
</script>
@endsection
