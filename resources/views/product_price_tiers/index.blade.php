@extends('layouts.app')

@section('content')
<h1>Price Tier Index
<a href='/product_price_tiers/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/product_price_tier/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($product_price_tiers->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Tier</th>
    <th><div align='center'>Cost Range</div></th> 
    <th><div align='center'>Public</div></th> 
    <th><div align='center'>Sponsor</div></th> 
	<!--
    <th>Outpatient</th> 
	<th>Inpatient</th>
	-->
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_price_tiers as $tier)
	<tr>
			<td>
					<a href='{{ URL::to('product_price_tiers/'. $tier->tier_id . '/edit') }}'>
						{{$tier->charge->charge_name}}
					</a>
			</td>
			<td align='center'>
				@if (!empty($tier->tier_min) || !empty($tier->tier_max))
					@if (!$tier->tier_min) < @endif
					@if (!$tier->tier_max) > @endif
					@if ($tier->tier_min>0) {{ number_format($tier->tier_min,2) }} @endif
					@if ($tier->tier_min && $tier->tier_max) - @endif
					@if ($tier->tier_max>0) {{ number_format($tier->tier_max,2) }} @endif
				@endif
			</td>
			<td align='center'>
					@if ($tier->tier_outpatient_multiplier)
					x {{$tier->tier_outpatient_multiplier}} 
							@if ($tier->tier_outpatient_limit)
							(Min {{$tier->tier_outpatient_limit}})
							@endif
					@endif
					@if ($tier->tier_outpatient>0) {{ number_format($tier->tier_outpatient,2) }} @endif
			</td>
			<td align='center'>
					@if ($tier->tier_inpatient_multiplier)
					x {{$tier->tier_inpatient_multiplier}}
							@if ($tier->tier_inpatient_limit)
							(Min {{$tier->tier_inpatient_limit}})
							@endif
					@endif
					@if ($tier->tier_inpatient>0) {{ number_format($tier->tier_inpatient,2) }} @endif
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_price_tiers/delete/'. $tier->tier_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_price_tiers->appends(['search'=>$search])->render() }}
	@else
	{{ $product_price_tiers->render() }}
@endif
<br>
@if ($product_price_tiers->total()>0)
	{{ $product_price_tiers->total() }} records found.
@else
	No record found.
@endif
@endsection
