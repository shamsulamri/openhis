@extends('layouts.app')

@section('content')
<h1>Future Orders
</h1>
<form action='/future/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($futures->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Patient</th>
    <th>MRN</th>
    <th>Order</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($futures as $future)
	<tr>
			<td>
					{{ (DojoUtility::dateLongFormat($future->orderInvestigation->investigation_date)) }}
			</td>
			<td>
					<a href='{{ URL::to('order_investigations/'. $future->orderInvestigation->id.'/edit_date') }}'>
					{{$future->consultation->encounter->patient->patient_name}}
					</a>
			</td>
			<td>
					{{$future->consultation->encounter->patient->patient_mrn}}
			</td>
			<td>
					{{$future->product->product_name}}
					<br>
					<small>{{$future->product->category->category_name}}<small>
			</td>
			<td align='right'>
			@if (empty($encounter_helper->getActiveEncounter($future->consultation->encounter->patient_id)))
					<a class='btn btn-primary pull-right' data-toggle="tooltip" data-placement="top" title="Start Encounter" href='{{ URL::to('encounters/create?patient_id='. $future->consultation->encounter->patient->patient_id.'&order_id='.$future->order_id) }}'>
						<i class="fa fa-flag"></i>
					</a>
			@else
				In Queue	
			@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $futures->appends(['search'=>$search])->render() }}
	@else
	{{ $futures->render() }}
@endif
<br>
@if ($futures->total()>0)
	{{ $futures->total() }} records found.
@else
	No record found.
@endif
@endsection
