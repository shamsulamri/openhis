@extends('layouts.app')

@section('content')
<h1>Drug Frequency List
<a href='/drug_frequencies/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_frequency/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_frequencies->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Label</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_frequencies as $drug_frequency)
	<tr>
			<td>
					<a href='{{ URL::to('drug_frequencies/'. $drug_frequency->frequency_code . '/edit') }}'>
						{{$drug_frequency->frequency_name}}
					</a>
			</td>
			<td>
					{{$drug_frequency->frequency_code}}
			</td>
			<td>
					{{$drug_frequency->frequency_label}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_frequencies/delete/'. $drug_frequency->frequency_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_frequencies->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_frequencies->render() }}
@endif
<br>
@if ($drug_frequencies->total()>0)
	{{ $drug_frequencies->total() }} records found.
@else
	No record found.
@endif
@endsection
