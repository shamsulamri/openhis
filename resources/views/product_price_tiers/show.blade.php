
@extends('layouts.app')

@section('content')
<h1>Price Tier</h1>
<h3>
{{ $tier->charge_name }}
</h3>

<table class="table table-hover">
 <thead>
	<tr> 
    <th>Cost Range</th> 
    <th>Public</th> 
	<th>Sponsor</th>
	</tr>
  </thead>
	<tbody>
@foreach ($tiers as $tier)
	<tr>
			<td>
				@if (!empty($tier->tier_min) || !empty($tier->tier_max))
					@if (!$tier->tier_min) < @endif
					@if (!$tier->tier_max) > @endif
					{{$tier->tier_min}}
					@if ($tier->tier_min && $tier->tier_max) - @endif
					{{$tier->tier_max}}
				@endif
			</td>
			<td>
					@if ($tier->tier_outpatient_multiplier)
					x {{$tier->tier_outpatient_multiplier}} 
							@if ($tier->tier_outpatient_limit)
							(Min {{$tier->tier_outpatient_limit}})
							@endif
					@endif
					{{ $tier->tier_outpatient }}
			</td>
			<td>
					@if ($tier->tier_inpatient_multiplier)
					x {{$tier->tier_inpatient_multiplier}}
							@if ($tier->tier_inpatient_limit)
							(Min {{$tier->tier_inpatient_limit}})
							@endif
					@endif
					{{ $tier->tier_inpatient }}
			</td>
	</tr>
@endforeach
</tbody>
</table>

@endsection
