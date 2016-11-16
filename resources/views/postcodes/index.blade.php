@extends('layouts.app')

@section('content')
<h1>Postcode Index</h1>
<br>
<form action='/postcode/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/postcodes/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($postcodes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>postcode</th>
    <th>postcode</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($postcodes as $postcode)
	<tr>
			<td>
					<a href='{{ URL::to('postcodes/'. $postcode->postcode . '/edit') }}'>
						{{$postcode->postcode}}
					</a>
			</td>
			<td>
					{{$postcode->postcode}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('postcodes/delete/'. $postcode->postcode) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $postcodes->appends(['search'=>$search])->render() }}
	@else
	{{ $postcodes->render() }}
@endif
<br>
@if ($postcodes->total()>0)
	{{ $postcodes->total() }} records found.
@else
	No record found.
@endif
@endsection
