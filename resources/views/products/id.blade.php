@if (!empty($product))
<div class='well'>
		<h4>{{ $product->product_name }}</h4>
		<h6>{{ $product->product_code }}</strong></h6>
		@if ($product->product_on_hand>0)
		<h5>On Hand: {{ $product->product_on_hand }}</h5>
		@endif
</div>
@endif
