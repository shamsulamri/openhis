@extends('layouts.app')

@section('content')
<h1>Referral List
<a href='/referrals/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/referral/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($referrals->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($referrals as $referral)
	<tr>
			<td>
					<a href='{{ URL::to('referrals/'. $referral->referral_code . '/edit') }}'>
						{{$referral->referral_name}}
					</a>
			</td>
			<td>
					{{$referral->referral_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('referrals/delete/'. $referral->referral_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $referrals->appends(['search'=>$search])->render() }}
	@else
	{{ $referrals->render() }}
@endif
<br>
@if ($referrals->total()>0)
	{{ $referrals->total() }} records found.
@else
	No record found.
@endif
@endsection
