@if (!empty($product))
<div class="row border-bottom gray-bg">
	<div class='col-sm-12'>
		<h2>{{ $product->product_name }}</h2>
		<h6>{{ $product->product_code }}</strong></h6>
		<!--
		@if ($product->product_on_hand>0)
		<span class='label label-default'>On Hand: {{ $product->product_on_hand }}</span>
		<br>
		@endif
		<br>
		-->
	</div>
</div>
@endif
<br>
	@can('product_information_edit')
		<a class='btn btn-default' href='{{ URL::to('products/'. $product->product_code.'/edit') }}'>
			<span class='fa fa-glass' aria-hidden='true'></span><br>Edit<br>Product
		</a>
	@endcan
@if ($product->product_stocked==1)
		<a class='btn btn-default' href='{{ URL::to('stocks/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-road' aria-hidden='true'></span><br>Stock<br>Movements
		</a>
@endif
@can('module-inventory')
		@if ($product->product_bom==1)
				<a class='btn btn-default' href='{{ URL::to('bill_materials/'. $product->product_code) }}'>
					<span class='fa fa-cubes' aria-hidden='true'></span><br>Bill of<br> Materials
				</a>
		@endif
		@if ($product->product_bom==1)
				<a class='btn btn-default' href='{{ URL::to('build_assembly/'. $product->product_code) }}'>
					<span class='glyphicon glyphicon-cog' aria-hidden='true'></span><br>Build<br>Assembly
				</a>
		@endif
		@if ($product->product_bom==1)
				<a class='btn btn-default' href='{{ URL::to('explode_assembly/'. $product->product_code) }}'>
					<span class='glyphicon glyphicon-fire' aria-hidden='true'></span><br>Explode<br>Assembly
				</a>
		@endif
		@if ($product->category_code=='drugs')
				<a class='btn btn-default' href='{{ URL::to('drug_prescriptions/'. $product->product_code.'/edit') }}'>
					<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span><br>Drug<br>Prescription
				</a>
		@endif
		<a class='btn btn-default' href='{{ URL::to('product_maintenances/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-wrench' aria-hidden='true'></span><br>Product<br>Maintenance
		</a>
@endcan

@can('loan_function')
		<a class='btn btn-default' href='{{ URL::to('loans/request/'. $product->product_code) }}'>
			<span class='glyphicon glyphicon-transfer' aria-hidden='true'></span><br>Loan<br>Request
		</a>
@endcan
