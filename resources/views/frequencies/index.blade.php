@extends('layouts.app')

@section('content')
<h1>Frequency List
<a href='/frequencies/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/frequency/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($frequencies->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($frequencies as $frequency)
	<tr>
			<td>
					<a href='{{ URL::to('frequencies/'. $frequency->frequency_code . '/edit') }}'>
						{{$frequency->frequency_name}}
					</a>
			</td>
			<td>
					{{$frequency->frequency_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('frequencies/delete/'. $frequency->frequency_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $frequencies->appends(['search'=>$search])->render() }}
	@else
	{{ $frequencies->render() }}
@endif
<br>
@if ($frequencies->total()>0)
	{{ $frequencies->total() }} records found.
@else
	No record found.
@endif
@endsection
