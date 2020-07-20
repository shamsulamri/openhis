@extends('layouts.app')

@section('content')
@include('patients.id_only')

<?php
	$category = null;
?>
<h1>Order Sheet</h1>

{{ Form::open(['url'=>'/order_sheet/'.$encounter->encounter_id]) }}
@if (Auth::user()->author_id==19)
<a class='btn btn-default pull-right' href='/bill_items/generate/{{ $encounter->encounter_id }}'>Back to Final Bill</a>
@endif
{{ Form::submit('Update', ['class'=>'btn btn-primary']) }}
<br>
<br>
<table class="table table-hover">
 <thead>
	<tr> 
    <th></th> 
    <th>Product</th> 
    <th>Orderer</th> 
    <th>Update / Cancel</th> 
    <th><div align='right'>Discount</div></th> 
    <th><div align='right'>Quantity</div></th> 
    <th></th> 
	</tr>
</thead>
	<tbody>
@foreach ($orders as $order)
	@if (array_key_exists($order->category_code, $authorized_categories))
	<?php
		$is_cancel = false;
		if ($order->cancel_id) $is_cancel = true;
	?>
	@if ($category != $order->category_name)
	<tr style='background-color:#EFEFEF'>
		<td colspan='1'>
				<!-- Single button -->
				<div class="btn-group">
				  <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
					@foreach ($bookmarks as $bookmark)
					<li><a href="#{{ $bookmark->category_code }}">{{ $bookmark->category_name }}</a></li>
					@endforeach
				  </ul>
				</div>
		</td>
		<td colspan='6'>
				<strong>
				<div id='{{ $order->category_code }}'>
				{{ strtoupper($order->category_name) }}
				</div>
				</strong>
		</td>
	</tr>

	@endif
<?php
	if ($category != $order->category_name) {
			$category = $order->category_name;
	}
?>
	<tr>
		<td width='10'>
			@if (!$is_cancel)
			{{ Form::checkbox($order->order_id.'_completed', '1', $order->order_completed) }}
			@else
			{{ Form::checkbox($order->order_id.'_completed', '1', $order->order_completed, ['disabled'=>'disabled']) }}
			@endif
		</td>
		<td width='40%'>
				@if ($is_cancel)
						<strike>
						{{ $order->product->product_name }}
						</strike>
				@else
						{{ $order->product->product_name }}
				@endif
				<br>
				<small>
				{{ $order->product_code }} 
				</small>
		</td>
		<td width='20%'>
				<small>
				{{ DojoUtility::titleCase($order->orderer_name) }}<br>
				{{ DojoUtility::militaryFormat($order->order_at) }}
				</small>
				@if (empty($order->post_id))
				<span class="glyphicon glyphicon-exclamation-sign alert-danger"></span>
				@endif
		</td>
		<td width='20%'>
		<small>
				@if ($order->updated_by)
					{{ $order->update_name }}<br>
					{{ DojoUtility::militaryFormat($order->updated_at) }}
				@endif

				@if ($is_cancel)
					{{ $order->cancel_name }}<br>
					{{ $order->cancel_reason }}<br>
					{{ DojoUtility::militaryFormat($order->cancel_at) }}
				@endif
		</small>
		</td>
		<td width='80'>
		<div align='right'>
			@if (!$is_cancel)
				{{ Form::text($order->order_id.'_discount', $order->order_discount, ['id'=>$order->order_id.'_discount', 'class'=>'form-control']) }}
			@else
				{{ Form::text($order->order_id.'_discount', $order->order_discount?:" ", ['class'=>'form-control', 'disabled'=>'disabled']) }}
			@endif
		</div>
		</td>
		<td width='80'>
		<div align='right'>
			@if (!$is_cancel)
				{{ Form::text($order->order_id.'_supply', $order->order_quantity_supply, ['id'=>$order->order_id.'_supply', 'class'=>'form-control']) }}
			@else
				{{ Form::text($order->order_id.'_supply', $order->order_quantity_supply?:" ", ['class'=>'form-control', 'disabled'=>'disabled']) }}
			@endif
		</div>
		</td>
		<td>
		@if (!$is_cancel)
		<a class='btn btn-warning' href='{{ URL::to('/order_sheet/cancel/'. $order->order_id) }}'>
			<span class='glyphicon glyphicon-remove'></span>
		</a>
		@else
		<a class='btn btn-primary' href='{{ URL::to('/order_sheet/cancel/'. $order->cancel_id.'/edit') }}'>
			<span class='glyphicon glyphicon-pencil'></span>
		</a>
		@endif
		
		</td>
	</tr>
	@endif
@endforeach
	</tbody>
</table>
</form>
@endsection
