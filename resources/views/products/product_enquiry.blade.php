@if ($product)
<?php 
$on_hand = $stock_helper->getStockOnHand($product->product_code, $store_code);
$allocated = $stock_helper->getStockAllocated($product->product_code, $store_code);
$stock_limit = $product->getStockLimits($store_code);
$average_cost = $stock_helper->getStockAverageCost($product->product_code, $store_code);
?>
<div class='form-horizontal'>
	<!-- Information -->
	<div class='form-group'>
        <label for='product_name' class='col-sm-2 control-label'>Name</label>
        <div class='col-sm-10'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control']) }}
        </div>
    </div>

	<div class='form-group'>
        <label for='product_code' class='col-sm-2 control-label'>Code</label>
        <div class='col-sm-10'>
            {{ Form::label('product_name', $product->product_code, ['class'=>'form-control']) }}
        </div>
    </div>

	<div class='form-group'>
        <label for='product_name_other' class='col-sm-2 control-label'>Other</label>
        <div class='col-sm-10'>
            {{ Form::label('', $product->product_name_other, ['id'=>'product_name_other','class'=>'form-control','placeholder'=>'Long, generic or other name for the product','maxlength'=>'200']) }}
        </div>
    </div>

	<div class='form-group'>
        <label for='category_code' class='col-sm-2 control-label'>Category</label>
        <div class='col-sm-10'>
            	{{ Form::label('category_code', $product->category->category_name, ['class'=>'form-control']) }}
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						<label for='average_cost' class='col-sm-4 control-label'>Average Cost</label>
						<div class='col-sm-8'>
            				{{ Form::label('average_cost', number_format($average_cost,2) , ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<!--
					<div class='form-group'>
						{{ Form::label('form_code', 'Total Cost',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('form_code', number_format($average_cost*$on_hand, 2), ['class'=>'form-control']) }}
						</div>
					</div>
					-->
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						{{ Form::label('on_hand', 'On Hand',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('on_hand', $on_hand?:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group'>
						{{ Form::label('unit_code', 'Allocated',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('', $allocated?:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  '>
						{{ Form::label('status_code', 'Available',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', ($on_hand - $allocated)?:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  '>
						{{ Form::label('status_code', 'On Purchase',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', floatval($stock_helper->getStockOnPurchase($product->product_code, $store_code))?:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  '>
						{{ Form::label('status_code', 'Transfer In',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', floatval($stock_helper->getStockTransferIn($product->product_code, $store_code))?:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  '>
						{{ Form::label('status_code', 'Transfer Out',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', floatval($stock_helper->getStockTransferOut($product->product_code, $store_code))?:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  '>
						{{ Form::label('status_code', 'Min Limit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', $stock_limit?$stock_limit->limit_min:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  '>
						{{ Form::label('status_code', 'Max Limit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', $stock_limit?$stock_limit->limit_max:' 0 ', ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						<div class='col-sm-offset-4 col-sm-8'>
							@if ($product->product_stocked)
								<span class="fa fa-check-square-o" aria-hidden="true"></span>
							@else
								<span class="fa fa-square-o" aria-hidden="true"></span>
							@endif
							<label>Physical Stock</label><br> Check for product you want to manage stock level for.
						</div>
					</div>
			</div>
	</div>
</div>

@endif
