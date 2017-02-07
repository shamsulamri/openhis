@extends('layouts.app')

@section('content')
<h1>Future Order List
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
					{{$future->consultation->encounter->patient->patient_name}}
			</td>
			<td>
					{{$future->consultation->encounter->patient->patient_mrn}}
			</td>
			<td>
					{{$future->product->product_name}}
					<br>
					<small>{{$future->product->category->category_name}}<small>
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
