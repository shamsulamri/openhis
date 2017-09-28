@extends('layouts.app')

@section('content')
<h1>Bill Aging Index
<a href='/bill_agings/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bill_aging/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bill_agings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>age_amount</th>
    <th>encounter_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bill_agings as $bill_aging)
	<tr>
			<td>
					<a href='{{ URL::to('bill_agings/'. $bill_aging->encounter_id . '/edit') }}'>
						{{$bill_aging->age_amount}}
					</a>
			</td>
			<td>
					{{$bill_aging->encounter_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_agings/delete/'. $bill_aging->encounter_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bill_agings->appends(['search'=>$search])->render() }}
	@else
	{{ $bill_agings->render() }}
@endif
<br>
@if ($bill_agings->total()>0)
	{{ $bill_agings->total() }} records found.
@else
	No record found.
@endif
@endsection
