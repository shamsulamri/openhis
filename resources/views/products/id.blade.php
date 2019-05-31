@if (!empty($product))
<div class="row border-bottom gray-bg">
	<div class='col-sm-12'>
		<h2>{{ $product->product_name }}</h2>
		<h6>{{ $product->product_code }}</strong></h6>
		@if ($product->product_on_hand>0)
		<h3>
		Stock On Hand: 
					{{ DojoUtility::stockOnHand($product->product_code, Auth::user()->defaultStore()) }}
		</h3>
		@endif
		<br>
	</div>
</div>
@endif
<br>
	@can('product_information_edit')
		<a class='btn btn-default' href='{{ URL::to('products/'. $product->product_code.'/edit') }}'>
			<span class='fa fa-glass' aria-hidden='true'></span><br>Edit<br>Product
		</a>
		<a class='btn btn-default' href='{{ URL::to('product/uom/'. $product->product_code) }}'>
			<span class='fa fa-balance-scale' aria-hidden='true'></span><br>Unit of<br>Measure
		</a>
	@endcan
@if ($product->product_stocked==1)
		<a class='btn btn-default' href='{{ URL::to('inventory_batches/product/'. $product->product_code) }}'>
			<span class='fa fa-tags' aria-hidden='true'></span><br>Stock<br>Batches
		</a>
		<a class='btn btn-default' href='{{ URL::to('stock_limit/'. $product->product_code) }}'>
			<span class='fa fa-cart-plus' aria-hidden='true'></span><br>Stock<br>&nbsp;&nbsp;&nbsp;Limits&nbsp;&nbsp;&nbsp;
		</a>
@endif
@can('module-inventory')
		<a class='btn btn-default' href='{{ URL::to('bill_materials/'. $product->product_code) }}'>
			<span class='fa fa-cubes' aria-hidden='true'></span><br>Bill of<br> Materials
		</a>
		<a class='btn btn-default' href='{{ URL::to('build_assembly/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-cog' aria-hidden='true'></span><br>Build<br>Assembly
		</a>
		<a class='btn btn-default' href='{{ URL::to('explode_assembly/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-fire' aria-hidden='true'></span><br>Explode<br>Assembly
		</a>
		<!--
		<a class='btn btn-default' href='{{ URL::to('product_maintenances/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-wrench' aria-hidden='true'></span><br>Product<br>Maintenance
		</a>
		-->
@endcan

@can('loan_function')
		<a class='btn btn-default' href='{{ URL::to('loans/request/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-transfer' aria-hidden='true'></span><br>Product<br>Request
		</a>
@endcan

