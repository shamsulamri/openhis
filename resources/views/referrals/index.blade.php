@extends('layouts.app')

@section('content')
<h1>Referral List</h1>
<br>
<form action='/referral/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/referrals/create' class='btn btn-primary'>Create</a>
<br>
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
