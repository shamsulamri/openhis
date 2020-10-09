@extends('layouts.app')

@section('content')
<h1>Discount Rule Index
<a href='/discount_rules/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/discount_rule/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($discount_rules->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Sponsor</th>
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($discount_rules as $discount_rule)
	<tr>
			<td>
					<a href='{{ URL::to('discount_rules/'. $discount_rule->rule_id . '/edit') }}'>
						{{$discount_rule->sponsor->sponsor_name}}
					</a>
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('discount_rules/delete/'. $discount_rule->rule_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $discount_rules->appends(['search'=>$search])->render() }}
	@else
	{{ $discount_rules->render() }}
@endif
<br>
@if ($discount_rules->total()>0)
	{{ $discount_rules->total() }} records found.
@else
	No record found.
@endif
@endsection
