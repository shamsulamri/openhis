@extends('layouts.app')

@section('content')
<h1>Bill Total Index
<a href='/bill_totals/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bill_total/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bill_totals->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>bill_total</th>
    <th>encounter_id</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($bill_totals as $bill_total)
	<tr>
			<td>
					<a href='{{ URL::to('bill_totals/'. $bill_total->encounter_id . '/edit') }}'>
						{{$bill_total->bill_total}}
					</a>
			</td>
			<td>
					{{$bill_total->encounter_id}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_totals/delete/'. $bill_total->encounter_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bill_totals->appends(['search'=>$search])->render() }}
	@else
	{{ $bill_totals->render() }}
@endif
<br>
@if ($bill_totals->total()>0)
	{{ $bill_totals->total() }} records found.
@else
	No record found.
@endif
@endsection
