@extends('layouts.app')

@section('content')
<h1>Entitlement Index
<a href='/entitlements/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/entitlement/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($entitlements->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>entitlement_code</th>
    <th>entitlement_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($entitlements as $entitlement)
	<tr>
			<td>
					<a href='{{ URL::to('entitlements/'. $entitlement->entitlement_code . '/edit') }}'>
						{{$entitlement->entitlement_code}}
					</a>
			</td>
			<td>
					{{$entitlement->entitlement_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('entitlements/delete/'. $entitlement->entitlement_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $entitlements->appends(['search'=>$search])->render() }}
	@else
	{{ $entitlements->render() }}
@endif
<br>
@if ($entitlements->total()>0)
	{{ $entitlements->total() }} records found.
@else
	No record found.
@endif
@endsection
