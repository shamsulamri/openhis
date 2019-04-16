
<div class="row">
	@foreach ($investigations as $investigation)
	<div class="col-xs-{{ 12/$investigations->count() }}">
			<div class='form-group'>
				<h4>{{ $investigation->set_name }}</h4>
			</div>
				@foreach ($investigation->products() as $product)
			<div class='form-group'>
				<div class='col-md-12'>
				<label>
						{{ Form::checkbox($product->product_code, '1', in_array($product->product_code, $orders)?'true':null , ['id'=>$product->product_code, 'onchange'=>'addOrder("'.$product->product_code.'")']) }} 
				&nbsp;{{ ucwords(strtolower($product->product_name)) }}
        		</label>
				</div>
			</div>
				@endforeach
	</div>
	@endforeach
</div>
